<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * PaymentsFixture
 */
class PaymentsFixture extends TestFixture
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
                'booking_id' => 1,
                'payment_date_time' => '2026-01-10 15:38:45',
                'payment_method' => 'Lorem ipsum dolor sit amet',
                'total_price' => 'Lorem ipsum d',
                'status' => 1,
                'created' => '2026-01-10 15:38:45',
                'modified' => '2026-01-10 15:38:45',
            ],
        ];
        parent::init();
    }
}
