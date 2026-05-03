<?php
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'waste_db';

$conn = mysqli_connect($host, $user, $password);
if (!$conn) die('Connection failed: ' . mysqli_connect_error());

mysqli_query($conn, "CREATE DATABASE IF NOT EXISTS $database");
mysqli_select_db($conn, $database);

mysqli_query($conn, "CREATE TABLE IF NOT EXISTS waste_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    waste_type VARCHAR(50) NOT NULL,
    location VARCHAR(255) NOT NULL,
    description TEXT,
    contact_name VARCHAR(100) NOT NULL,
    contact_phone VARCHAR(20) NOT NULL,
    status ENUM('pending', 'assigned', 'collected') DEFAULT 'pending',
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

mysqli_close($conn);
echo 'Database initialized successfully!';
?>
