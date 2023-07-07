<?php
$host = 'localhost';
$db   = 'mahesh';
$user = 'root';
$pass = '12102001';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$opt = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,      // Set error mode to exception
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,             // Set default fetch mode to associative array
    PDO::ATTR_EMULATE_PREPARES   => false,                        // Disable emulated prepared statements
];
try {
    // Create a new PDO instance with the specified database connection details
    $conn = new PDO($dsn, $user, $pass, $opt);
} catch (\PDOException $e) {
    // If an error occurs during the PDO connection, throw a PDOException
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
