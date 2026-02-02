<?php
declare(strict_types=1);

namespace App\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\ORM\Locator\LocatorAwareTrait;

class CheckBookingCommand extends Command
{
    use LocatorAwareTrait;

    public function execute(Arguments $args, ConsoleIo $io)
    {
        $bookingsTable = $this->fetchTable('Bookings');

        // Find Booking MC-00062 (ID 62)
        $booking = $bookingsTable->findById(62)->contain(['Tickets' => ['Seats']])->first();

        if ($booking) {
            $io->out("Booking Found: ID " . $booking->id);
            $io->out("Quantity: " . $booking->quantity);
            $io->out("Ticket Count: " . count($booking->tickets));
            foreach ($booking->tickets as $ticket) {
                $seatLabel = ($ticket->seat) ? $ticket->seat->seat_row . $ticket->seat->seat_number : "Unknown Seat";
                $io->out(" - Ticket: " . $ticket->id . " | Seat: " . $seatLabel);
            }
        } else {
            $io->out("Booking #MC-00062 not found. Listing recent bookings:");
            $recent = $bookingsTable->find()->order(['id' => 'DESC'])->limit(5)->all();
            foreach ($recent as $b) {
                $io->out(" - ID: " . $b->id . " | Qty: " . $b->quantity);
            }
        }
    }
}
