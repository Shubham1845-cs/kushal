<?php
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'complaint_mgmt_db';

$conn = mysqli_connect($host, $user, $password, $database);
if (!$conn) {
    die('Database connection failed. Please run <a href="init_db.php">init_db.php</a> first.');
}
?>
