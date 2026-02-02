<?php
require 'config/bootstrap.php';
use Cake\Datasource\ConnectionManager;

$connection = ConnectionManager::get('default');

$queries = [
    "ALTER TABLE customers ADD COLUMN is_verified TINYINT(1) DEFAULT 0 AFTER status",
    "ALTER TABLE customers ADD COLUMN verification_token VARCHAR(255) NULL AFTER is_verified",
    "UPDATE customers SET is_verified = 1",

    "ALTER TABLE staffs ADD COLUMN is_verified TINYINT(1) DEFAULT 0 AFTER status",
    "ALTER TABLE staffs ADD COLUMN verification_token VARCHAR(255) NULL AFTER is_verified",
    "UPDATE staffs SET is_verified = 1"
];

foreach ($queries as $query) {
    echo "Executing: $query\n";
    try {
        $connection->execute($query);
        echo " Success!\n";
    } catch (\Exception $e) {
        echo " Error: " . $e->getMessage() . "\n";
    }
}
echo "MIGRATION_COMPLETE\n";
