<?php
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'login_db';

$connection = mysqli_connect($host, $user, $password);

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

// Create database
$sql = "CREATE DATABASE IF NOT EXISTS $database";
mysqli_query($connection, $sql);

// Connect to database
mysqli_select_db($connection, $database);

// Create users table
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
mysqli_query($connection, $sql);

mysqli_close($connection);
echo "Database initialized successfully!";
?>
