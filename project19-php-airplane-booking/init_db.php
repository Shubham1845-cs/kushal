<?php
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'airplane_db';

$conn = mysqli_connect($host, $user, $password);
if (!$conn) die('Connection failed: ' . mysqli_connect_error());

mysqli_query($conn, "CREATE DATABASE IF NOT EXISTS $database");
mysqli_select_db($conn, $database);

mysqli_query($conn, "CREATE TABLE IF NOT EXISTS seats (
    id INT AUTO_INCREMENT PRIMARY KEY,
    seat_number VARCHAR(10) NOT NULL UNIQUE,
    class VARCHAR(20) NOT NULL,
    is_booked TINYINT(1) DEFAULT 0,
    passenger_name VARCHAR(100),
    passenger_email VARCHAR(100),
    booked_at TIMESTAMP NULL
)");

// Drop existing seats and recreate with only 6 seats
mysqli_query($conn, "TRUNCATE TABLE seats");

// Row 1: Business class - seats A, B, C (3 seats)
foreach (['A','B','C'] as $s) {
    $seat = '1' . $s;
    mysqli_query($conn, "INSERT INTO seats (seat_number, class) VALUES ('$seat', 'Business')");
}
// Row 2: Economy class - seats A, B, C (3 seats)
foreach (['A','B','C'] as $s) {
    $seat = '2' . $s;
    mysqli_query($conn, "INSERT INTO seats (seat_number, class) VALUES ('$seat', 'Economy')");
}

mysqli_close($conn);
echo 'Database initialized successfully! Seats created.';
?>
