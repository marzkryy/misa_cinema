<?php
// Hardcoded because env() is not available in standalone
$host = 'localhost';
$database = 'misacinema';
$username = 'root';
$password = '';

try {
    $dsn = "mysql:host=$host;dbname=$database;charset=utf8";
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

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
            $pdo->exec($query);
            echo " Success!\n";
        } catch (PDOException $e) {
            echo " Info/Error: " . $e->getMessage() . "\n";
        }
    }
} catch (PDOException $e) {
    echo "Connection Error: " . $e->getMessage() . "\n";
}
echo "MIGRATION_COMPLETE\n";
