<?php
declare(strict_types=1);

use Migrations\AbstractSeed;

/**
 * Staffs seed.
 */
class StaffsSeed extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeds is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html
     *
     * @return void
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'Administrator',
                'email' => 'admin@gmail.com',
                'password' => (new \Cake\Auth\DefaultPasswordHasher())->hash('123'),
                'phone' => '0000000000',
                'role' => 'admin',
                'status' => 1,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
        ];

        $table = $this->table('staffs');
        $table->insert($data)->save();
    }
}
