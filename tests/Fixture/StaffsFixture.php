<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * StaffsFixture
 */
class StaffsFixture extends TestFixture
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
                'name' => 'Lorem ipsum dolor sit amet',
                'phone' => 'Lorem ipsum d',
                'email' => 'Lorem ipsum dolor sit amet',
                'status' => 1,
                'created' => '2026-01-10 21:05:45',
                'modified' => '2026-01-10 21:05:45',
            ],
        ];
        parent::init();
    }
}
