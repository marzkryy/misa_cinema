<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ShowsFixture
 */
class ShowsFixture extends TestFixture
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
                'show_title' => 'Lorem ipsum dolor sit amet',
                'avatar' => 'Lorem ipsum dolor sit amet',
                'avatar_dir' => 'Lorem ipsum dolor sit amet',
                'genre' => 'Lorem ipsum dolor sit amet',
                'show_time' => '15:38:59',
                'show_date' => '2026-01-10',
                'status' => 1,
                'created' => '2026-01-10 15:38:59',
                'modified' => '2026-01-10 15:38:59',
            ],
        ];
        parent::init();
    }
}
