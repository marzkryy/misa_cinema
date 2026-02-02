<?php
namespace App\Command;

use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\Command;
use Cake\ORM\TableRegistry;

class ScanBrokenBookingsCommand extends Command
{
    public function execute(Arguments $args, ConsoleIo $io)
    {
        $io->out("Scanning for bookings with missing tickets...");

        $bookingsTable = TableRegistry::getTableLocator()->get('Bookings');
        $ticketsTable = TableRegistry::getTableLocator()->get('Tickets');

        // Find bookings that are confirmed (status 1)
        $bookings = $bookingsTable->find()->where(['status' => 1])->all();

        $brokenCount = 0;

        foreach ($bookings as $booking) {
            $ticketCount = $ticketsTable->find()->where(['booking_id' => $booking->id])->count();

            if ($ticketCount != $booking->quantity) {
                $brokenCount++;
                $io->out(sprintf(
                    "Broken Booking Found: #MC-%05d | Qty: %d | Tickets Found: %d | Show ID: %d",
                    $booking->id,
                    $booking->quantity,
                    $ticketCount,
                    $booking->show_id
                ));
            }
        }

        if ($brokenCount === 0) {
            $io->out("No broken bookings found.");
        } else {
            $io->out(sprintf("Found %d broken bookings.", $brokenCount));
        }
    }
}
