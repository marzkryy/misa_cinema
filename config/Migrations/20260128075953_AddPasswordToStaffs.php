<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class AddPasswordToStaffs extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    public function change(): void
    {
        $table = $this->table('staffs');
        
        if (!$table->hasColumn('password')) {
            $table->addColumn('password', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ]);
        }
        
        if (!$table->hasColumn('role')) {
            $table->addColumn('role', 'string', [
                'default' => 'admin',
                'limit' => 50,
                'null' => false,
            ]);
        }
        
        $table->update();
    }
}
