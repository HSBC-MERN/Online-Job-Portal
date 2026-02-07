<?php
$DB_HOST = 'localhost';
$DB_NAME = 'online_job_portal';
$DB_USER = 'root';
$DB_PASS = ''; // add your MySQL password here if any

try {
    $pdo = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8mb4", $DB_USER, $DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Database connected successfully!"; // Uncomment to test
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
