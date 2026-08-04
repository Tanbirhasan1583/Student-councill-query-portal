<?php
$host = 'localhost';        // অথবা 'localhost'
$db   = 'student_council';  // তোমার ডাটাবেস নাম
$user = 'root';             // default XAMPP user
$pass = '';                 // default XAMPP password ফাঁকা থাকে
$charset = 'utf8mb4';

// এখানে port যোগ করো
$dsn = "mysql:host=$host;port=3307;dbname=$db;charset=$charset;port=3307";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
