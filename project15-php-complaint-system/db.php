<?php
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'complaint_db';

$conn = mysqli_connect($host, $user, $password, $database);
if (!$conn) die('Connection failed');

mysqli_set_charset($conn, 'utf8');
?>
