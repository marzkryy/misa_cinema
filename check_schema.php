<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/config/bootstrap.php';
use Cake\ORM\TableRegistry;
$halls = TableRegistry::getTableLocator()->get('Halls');
$schema = $halls->getSchema();
print_r($schema->columns());
