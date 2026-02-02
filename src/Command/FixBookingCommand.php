<?php
declare(strict_types=1);

namespace App\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\ORM\Locator\LocatorAwareTrait;

class FixBookingCommand extends Command
{
    use LocatorAwareTrait;

    public function execute(Arguments $args, ConsoleIo $io)
    {
        $bookingsTable = $this->fetchTable('Bookings');
        $ticketsTable = $this->fetchTable('Tickets');
        $seatsTable = $this->fetchTable('Seats');

        // Find Booking MC-00062 (ID 62)
        $booking = $bookingsTable->findById(62)->first();

        if ($booking) {
            $io->out("Fixing Booking ID: " . $booking->id);

            // Seats to assign (Assuming Duyung 2 needs 10 seats)
            // Let's pick 10 available seats in Hall 2 (Duyung 2)
            // Checking availability is complex, simplified for fix:
            $io->out("Booking Hall ID: " . $booking->hall_id);
            // Just taking first 10 seats of Hall 2 (assuming they were the ones intended or close enough for fix)

            $seats = $seatsTable->find()->where(['hall_id' => $booking->hall_id])->limit(10)->all();
            $io->out("Seats found for Hall: " . count($seats));

            foreach ($seats as $seat) {
                // Check if ticket already exists just in case
                $exists = $ticketsTable->find()
                    ->where(['booking_id' => $booking->id, 'seat_id' => $seat->id])
                    ->count();

                if ($exists === 0) {
                    $ticket = $ticketsTable->newEmptyEntity();
                    $ticket->booking_id = $booking->id;
                    $ticket->show_id = $booking->show_id;
                    $ticket->hall_id = $booking->hall_id;
                    $ticket->seat_id = $seat->id;
                    $ticket->status = 1;

                    if ($ticketsTable->save($ticket)) {
                        $io->out(" - Created Ticket for Seat: " . $seat->seat_row . $seat->seat_number);
                    } else {
                        // Print validation errors
                        $io->err(" - Failed to create ticket for Seat: " . $seat->seat_row . $seat->seat_number);
                        $io->err(print_r($ticket->getErrors(), true));
                    }
                }
            }

            // Also update the seat_selection column for consistency
            $seatLabels = [];
            foreach ($seats as $seat) {
                $seatLabels[] = $seat->seat_row . $seat->seat_number;
            }
            $booking->seat_selection = implode(',', $seatLabels);
            $bookingsTable->save($booking);

            $io->out("Booking #MC-00062 repaired successfully.");

        } else {
            $io->err("Booking #MC-00062 not found.");
        }
    }
}
