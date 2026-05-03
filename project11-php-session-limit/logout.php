<?php
session_start();

$db_file = 'sessions.json';

// Remove current session from active sessions
if (file_exists($db_file)) {
    $activeSessions = json_decode(file_get_contents($db_file), true) ?? [];
    unset($activeSessions[session_id()]);
    file_put_contents($db_file, json_encode($activeSessions));
}

// Destroy session
session_destroy();

// Redirect to login
header("Location: login.php?msg=Logged out successfully");
exit();
?>
