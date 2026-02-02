<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * CustomersFixture
 */
class CustomersFixture extends TestFixture
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
                'created' => '2026-01-10 15:41:24',
                'modified' => '2026-01-10 15:41:24',
            ],
        ];
        parent::init();
    }
}
