<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * BookingsFixture
 */
class BookingsFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'cust_id' => 1,
                'show_id' => 1,
                'book_date_time' => '2026-01-11 11:16:57',
                'hall_id' => 1,
                'seat_id' => 1,
                'quantity' => 1,
                'ticket_price' => 'Lorem ipsum d',
                'status' => 1,
                'created' => '2026-01-11 11:16:57',
                'modified' => '2026-01-11 11:16:57',
            ],
        ];
        parent::init();
    }
}
