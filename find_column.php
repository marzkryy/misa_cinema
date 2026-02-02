<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/config/bootstrap.php';
use Cake\Datasource\ConnectionManager;
$c = ConnectionManager::get('default');
$tables = $c->execute('SHOW TABLES')->fetchAll();
foreach($tables as $t) {
    $table = array_values($t)[0];
    foreach($c->execute("DESCRIBE $table") as $row) {
        if ($row['Field'] == 'hall_price') {
            echo "--- Table: $table has hall_price ---\n";
        }
    }
}
echo "Done search.\n";
