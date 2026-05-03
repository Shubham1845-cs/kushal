<?php
session_start();

if (isset($_SESSION['login_time'])) {
    $_SESSION['login_time'] = time();
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error']);
}
?>
