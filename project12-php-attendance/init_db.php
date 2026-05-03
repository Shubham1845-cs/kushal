<?php
// Database configuration
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'attendance_db';

$connection = mysqli_connect($host, $user, $password);

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

// Create database
$sql = "CREATE DATABASE IF NOT EXISTS $database";
mysqli_query($connection, $sql);

// Connect to database
mysqli_select_db($connection, $database);

// Create students table
$sql = "CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    roll_no VARCHAR(20) NOT NULL UNIQUE,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
mysqli_query($connection, $sql);

// Create attendance table
$sql = "CREATE TABLE IF NOT EXISTS attendance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    date DATE NOT NULL,
    present BOOLEAN DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id),
    UNIQUE KEY unique_attendance (student_id, date)
)";
mysqli_query($connection, $sql);

// Insert sample students if empty
$check = mysqli_query($connection, "SELECT COUNT(*) as count FROM students");
$row = mysqli_fetch_assoc($check);

if ($row['count'] == 0) {
    $samples = [
        ['001', 'Rahul Kumar'],
        ['002', 'Priya Singh'],
        ['003', 'Amit Patel'],
        ['004', 'Neha Sharma'],
        ['005', 'Vikram Singh']
    ];

    foreach ($samples as $student) {
        $email = strtolower(str_replace(' ', '.', $student[1])) . '@example.com';
        $sql = "INSERT INTO students (roll_no, name, email) VALUES ('{$student[0]}', '{$student[1]}', '$email')";
        mysqli_query($connection, $sql);
    }
}

mysqli_close($connection);
echo "Database initialized successfully!";
?>
