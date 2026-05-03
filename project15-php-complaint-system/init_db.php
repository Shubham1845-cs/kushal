<?php
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'complaint_db';

$conn = mysqli_connect($host, $user, $password);
if (!$conn) die('Connection failed: ' . mysqli_connect_error());

// Create database
mysqli_query($conn, "CREATE DATABASE IF NOT EXISTS $database");
mysqli_select_db($conn, $database);

// Create users table
mysqli_query($conn, "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('student', 'admin') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// Create complaints table
mysqli_query($conn, "CREATE TABLE IF NOT EXISTS complaints (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    description TEXT NOT NULL,
    category VARCHAR(50) NOT NULL,
    status ENUM('open', 'in-progress', 'resolved') DEFAULT 'open',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES users(id)
)");

// Insert default admin if not exists
$checkAdmin = mysqli_query($conn, "SELECT id FROM users WHERE username='admin' AND role='admin'");
if (mysqli_num_rows($checkAdmin) == 0) {
    $adminPass = password_hash('admin123', PASSWORD_DEFAULT);
    mysqli_query($conn, "INSERT INTO users (username, password, role) VALUES ('admin', '$adminPass', 'admin')");
}

mysqli_close($conn);
echo 'Database initialized successfully!';
?>
