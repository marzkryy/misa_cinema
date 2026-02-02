<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/config/bootstrap.php';
use Cake\ORM\TableRegistry;
$seats = TableRegistry::getTableLocator()->get('Seats');
$schema = $seats->getSchema();
foreach($schema->columns() as $col) {
    echo $col . ": " . $schema->getColumn($col)['type'] . " (" . ($schema->getColumn($col)['length'] ?? 'N/A') . ")\n";
}
