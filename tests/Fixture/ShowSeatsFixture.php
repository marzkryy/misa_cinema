<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ShowSeatsFixture
 */
class ShowSeatsFixture extends TestFixture
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
                'show_id' => 1,
                'parent_seat_id' => 1,
                'seat_row' => 'Lor',
                'seat_number' => 'Lorem ip',
                'seat_type' => 'Lorem ipsum dolor sit amet',
                'seat_price' => 1.5,
                'status' => 1,
                'created' => '2026-01-30 07:38:05',
                'modified' => '2026-01-30 07:38:05',
            ],
        ];
        parent::init();
    }
}
