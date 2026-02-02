<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * SeatsFixture
 */
class SeatsFixture extends TestFixture
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
                'hall_id' => 1,
                'seat_type' => 'Lorem ipsum dolor sit amet',
                'seat_price' => 1,
                'status' => 1,
                'created' => '2026-01-10 20:53:28',
                'modified' => '2026-01-10 20:53:28',
            ],
        ];
        parent::init();
    }
}
