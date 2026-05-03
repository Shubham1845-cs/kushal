<?php
header('Content-Type: application/json');

$connection = new mysqli('localhost', 'root', '');
if ($connection->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed.']);
    exit;
}

$connection->query('CREATE DATABASE IF NOT EXISTS vit_results');
$connection->select_db('vit_results');

$connection->query('CREATE TABLE IF NOT EXISTS semester_results (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_name VARCHAR(100) NOT NULL,
    course VARCHAR(100) NOT NULL,
    subject_1_name VARCHAR(100) DEFAULT "Engineering Mathematics",
    subject_1_mse INT DEFAULT 0,
    subject_1_ese INT DEFAULT 0,
    subject_2_name VARCHAR(100) DEFAULT "Physics",
    subject_2_mse INT DEFAULT 0,
    subject_2_ese INT DEFAULT 0,
    subject_3_name VARCHAR(100) DEFAULT "Data Structures",
    subject_3_mse INT DEFAULT 0,
    subject_3_ese INT DEFAULT 0,
    subject_4_name VARCHAR(100) DEFAULT "Web Technology",
    subject_4_mse INT DEFAULT 0,
    subject_4_ese INT DEFAULT 0,
    grand_total INT DEFAULT 0,
    grand_percentage DECIMAL(5, 2) DEFAULT 0,
    overall_grade VARCHAR(2) DEFAULT "D",
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)');

$data = json_decode(file_get_contents('php://input'), true);

if ($data) {
    $studentName = $data['studentName'] ?? '';
    $course = $data['course'] ?? '';
    $marks = $data['marks'] ?? [];
    $grandTotal = $data['grandTotal'] ?? 0;
    $grandPercentage = $data['grandPercentage'] ?? 0;
    $overallGrade = $data['overallGrade'] ?? 'D';

    if (empty($studentName) || empty($course)) {
        echo json_encode(['success' => false, 'message' => 'Student name and course are required.']);
        exit;
    }

    $subjects = array_keys($marks);
    $stmt = $connection->prepare('INSERT INTO semester_results (
        student_name, course,
        subject_1_name, subject_1_mse, subject_1_ese,
        subject_2_name, subject_2_mse, subject_2_ese,
        subject_3_name, subject_3_mse, subject_3_ese,
        subject_4_name, subject_4_mse, subject_4_ese,
        grand_total, grand_percentage, overall_grade
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');

    if ($stmt) {
        $values = [
            $studentName, $course,
            $subjects[0] ?? 'Subject 1', $marks[$subjects[0]]['mse'] ?? 0, $marks[$subjects[0]]['ese'] ?? 0,
            $subjects[1] ?? 'Subject 2', $marks[$subjects[1]]['mse'] ?? 0, $marks[$subjects[1]]['ese'] ?? 0,
            $subjects[2] ?? 'Subject 3', $marks[$subjects[2]]['mse'] ?? 0, $marks[$subjects[2]]['ese'] ?? 0,
            $subjects[3] ?? 'Subject 4', $marks[$subjects[3]]['mse'] ?? 0, $marks[$subjects[3]]['ese'] ?? 0,
            $grandTotal, $grandPercentage, $overallGrade
        ];

        $types = 'sssiiisiiisiiiiid' . 's'; // string, string, string, int, int, string, int, int, string, int, int, string, int, int, int, decimal, string

        $stmt->bind_param('sssiissiiisiiidds', ...$values);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Result saved successfully!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error: ' . $stmt->error]);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Prepare failed: ' . $connection->error]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid data received.']);
}

$connection->close();
