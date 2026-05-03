<?php
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'student_crud_db';

$conn = mysqli_connect($host, $user, $password);
if (!$conn) die('Connection failed: ' . mysqli_connect_error());

mysqli_query($conn, "CREATE DATABASE IF NOT EXISTS $database");
mysqli_select_db($conn, $database);

mysqli_query($conn, "CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    course VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// Sample data
$check = mysqli_query($conn, "SELECT COUNT(*) as cnt FROM students");
$row = mysqli_fetch_assoc($check);
if ($row['cnt'] == 0) {
    mysqli_query($conn, "INSERT INTO students (name, email, course, phone) VALUES
        ('Rahul Kumar', 'rahul@example.com', 'Computer Science', '9876543210'),
        ('Priya Singh', 'priya@example.com', 'Information Technology', '9876543211'),
        ('Amit Patel', 'amit@example.com', 'Electronics', '9876543212')
    ");
}

mysqli_close($conn);
echo 'Database initialized successfully!';
?>
