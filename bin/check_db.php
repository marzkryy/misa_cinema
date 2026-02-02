<?php
use Cake\Datasource\ConnectionManager;

$connection = ConnectionManager::get('default');

$tables = ['customers', 'staffs'];
foreach ($tables as $table) {
    echo "Structure for $table:\n";
    try {
        $results = $connection->execute("DESCRIBE $table")->fetchAll('assoc');
        foreach ($results as $row) {
            echo " - {$row['Field']} ({$row['Type']})\n";
        }
    } catch (\Exception $e) {
        echo " Error: " . $e->getMessage() . "\n";
    }
    echo "\n";
}
