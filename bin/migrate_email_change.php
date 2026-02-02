<?php
// Standalone PDO script with hardcoded credentials
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
        "ALTER TABLE customers ADD COLUMN temp_email VARCHAR(255) NULL AFTER verification_token",
        "ALTER TABLE customers ADD COLUMN email_code VARCHAR(10) NULL AFTER temp_email",
        "ALTER TABLE customers ADD COLUMN email_code_expiry DATETIME NULL AFTER email_code",

        "ALTER TABLE staffs ADD COLUMN temp_email VARCHAR(255) NULL AFTER verification_token",
        "ALTER TABLE staffs ADD COLUMN email_code VARCHAR(10) NULL AFTER temp_email",
        "ALTER TABLE staffs ADD COLUMN email_code_expiry DATETIME NULL AFTER email_code"
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
