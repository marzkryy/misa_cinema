<?php
declare(strict_types=1);

namespace App\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\I18n\FrozenTime;
use Cake\ORM\Locator\LocatorAwareTrait;

class AutoCancelBookingsCommand extends Command
{
    use LocatorAwareTrait;

    public function execute(Arguments $args, ConsoleIo $io)
    {
        $io->out('Checking for expired pending bookings...');

        $bookingsTable = $this->fetchTable('Bookings');
        
        // Time limit: 15 minutes ago
        $scannedTime = FrozenTime::now()->subMinutes(15);

        // Find Pending bookings (status 0) older than 15 minutes
        $expiredBookings = $bookingsTable->find()
            ->where([
                'status' => 0,
                'book_date_time <' => $scannedTime
            ])
            ->all();

        if ($expiredBookings->count() > 0) {
            $count = 0;
            foreach ($expiredBookings as $booking) {
                // Delete the booking
                // Note: Tickets are only created upon Payment (Status 1), so pending bookings have no tickets.
                // However, we should also delete linked SeatLocks if they still exist (though strict SeatLocks expire in 10 mins).
                
                 if ($bookingsTable->delete($booking)) {
                     $count++;
                     $io->verbose("Deleted expired booking ID: #MC-" . str_pad((string)$booking->id, 5, '0', STR_PAD_LEFT));
                 }
            }
            $io->out("Successfully auto-cancelled {$count} expired pending bookings.");
        } else {
            $io->out('No expired pending bookings found.');
        }
    }
}
