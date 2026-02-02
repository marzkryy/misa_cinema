<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class AddResetTokens extends AbstractMigration
{
    /**
     * Change Method.
     *
     * @return void
     */
    public function change(): void
    {
        // For Staffs table
        $staffs = $this->table('staffs');
        if (!$staffs->hasColumn('reset_token')) {
            $staffs->addColumn('reset_token', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ]);
        }
        if (!$staffs->hasColumn('reset_expiry')) {
            $staffs->addColumn('reset_expiry', 'datetime', [
                'default' => null,
                'null' => true,
            ]);
        }
        $staffs->update();

        // For Customers table
        $customers = $this->table('customers');
        if (!$customers->hasColumn('reset_token')) {
            $customers->addColumn('reset_token', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ]);
        }
        if (!$customers->hasColumn('reset_expiry')) {
            $customers->addColumn('reset_expiry', 'datetime', [
                'default' => null,
                'null' => true,
            ]);
        }
        $customers->update();
    }
}
