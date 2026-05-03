<?php
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'complaint_mgmt_db';

$conn = mysqli_connect($host, $user, $password);
if (!$conn) die('Connection failed: ' . mysqli_connect_error());

mysqli_query($conn, "CREATE DATABASE IF NOT EXISTS $database");
mysqli_select_db($conn, $database);

mysqli_query($conn, "CREATE TABLE IF NOT EXISTS complaints (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    organization VARCHAR(100) NOT NULL,
    category VARCHAR(100) NOT NULL,
    subject VARCHAR(200) NOT NULL,
    description TEXT NOT NULL,
    status ENUM('open','in-progress','resolved','closed') DEFAULT 'open',
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

mysqli_close($conn);
echo 'Database initialized successfully!';
?>
