<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * HallsFixture
 */
class HallsFixture extends TestFixture
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
                'hall_type' => 'Lorem ipsum dolor sit amet',
                'hall_price' => 1,
                'status' => 1,
                'created' => '2026-01-10 21:19:28',
                'modified' => '2026-01-10 21:19:28',
            ],
        ];
        parent::init();
    }
}
