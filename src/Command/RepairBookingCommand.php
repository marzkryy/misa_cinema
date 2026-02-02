<?php
namespace App\Command;

use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\Command;
use Cake\ORM\TableRegistry;

class RepairBookingCommand extends Command
{
    public function execute(Arguments $args, ConsoleIo $io)
    {
        $bookingId = 68;
        $qty = 7;

        $bookingsTable = TableRegistry::getTableLocator()->get('Bookings');
        $ticketsTable = TableRegistry::getTableLocator()->get('Tickets');
        $seatsTable = TableRegistry::getTableLocator()->get('Seats');

        $booking = $bookingsTable->get($bookingId);
        $io->out("Repairing Booking #MC-" . $booking->id . "...");

        // Find existing tickets for this show to avoid collision
        $existingTickets = $ticketsTable->find()
            ->where(['Tickets.show_id' => $booking->show_id, 'Tickets.status' => 1])
            ->contain(['Seats'])
            ->all();

        $occupiedSeatIds = [];
        foreach ($existingTickets as $t) {
            if ($t->has('seat')) {
                $occupiedSeatIds[] = $t->seat_id;
            }
        }

        // Fetch seats for valid hall (Assuming Hall 3 for Indulge based on screenshot or just standard Hall)
        // Wait, Show 22 (from previous output) might be mapped to a specific Hall.
        // Let's check show's hall_id
        $show = TableRegistry::getTableLocator()->get('Shows')->get($booking->show_id);

        // Strategy: Just pick first N seats not in occupied list
        // Assuming we have generated seats for this hall.
        // If not, we might need to generate seats too. But let's assume seats exist.

        $availableSeats = $seatsTable->find()
            ->where(['hall_id' => $show->hall_id])
            ->where(function ($exp, $q) use ($occupiedSeatIds) {
                if (empty($occupiedSeatIds))
                    return $exp;
                return $exp->notIn('id', $occupiedSeatIds);
            })
            ->limit($qty)
            ->all();

        if ($availableSeats->count() < $qty) {
            $io->err("Not enough seats available to backfill!");
            // Check if seats exist at all
            $totalSeats = $seatsTable->find()->where(['hall_id' => $show->hall_id])->count();
            if ($totalSeats == 0) {
                $io->err("Hall has NO seats generated! We might need to generate them.");
                // Generate dummy seats for this hall
                $this->generateSeats($show->hall_id, $seatsTable, $io);
                // Retry fetch
                $availableSeats = $seatsTable->find()->where(['hall_id' => $show->hall_id])->limit($qty)->all();
            }
        }

        $ticketsCreated = 0;
        $seatNames = [];

        foreach ($availableSeats as $seat) {
            $ticket = $ticketsTable->newEmptyEntity();
            $ticket->booking_id = $booking->id;
            $ticket->show_id = $show->id;
            $ticket->hall_id = $show->hall_id; // Fix: Set explicit hall_id
            $ticket->seat_id = $seat->id;
            $ticket->ticket_price = ($booking->ticket_price / $qty); // Distribute price
            $ticket->status = 1; // Confirmed

            try {
                if ($ticketsTable->save($ticket)) {
                    $ticketsCreated++;
                    $seatNames[] = $seat->seat_row . $seat->seat_number;
                } else {
                    $io->err("Failed to save ticket for seat " . $seat->id);
                    $io->err(print_r($ticket->getErrors(), true));
                }
            } catch (\Exception $e) {
                $io->err("Exception: " . $e->getMessage());
            }
        }

        // Update booking with seat selection string if needed
        $booking->seat_selection = implode(',', $seatNames);
        $bookingsTable->save($booking);

        $io->out("Success! Created $ticketsCreated tickets: " . implode(', ', $seatNames));
    }

    private function generateSeats($hallId, $seatsTable, $io)
    {
        $io->out("Generating seats for Hall ID: $hallId");
        $rows = ['A', 'B', 'C', 'D', 'E'];
        $cols = 10;
        $count = 0;
        foreach ($rows as $row) {
            for ($c = 1; $c <= $cols; $c++) {
                $seat = $seatsTable->newEmptyEntity();
                $seat->hall_id = $hallId;
                $seat->seat_row = $row;
                $seat->seat_number = (string) $c;
                $seatsTable->save($seat);
                $count++;
            }
        }
        $io->out("Generated $count seats.");
    }
}
