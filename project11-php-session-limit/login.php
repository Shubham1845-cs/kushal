<?php
session_start();
$db_file = 'sessions.json';

// Load active sessions
$activeSessions = [];
if (file_exists($db_file)) {
    $activeSessions = json_decode(file_get_contents($db_file), true) ?? [];
}

// Clean expired sessions
$activeSessions = array_filter($activeSessions, function($session) {
    return (time() - $session['timestamp']) < 300; // 5 minute timeout
});

// Save cleaned sessions
file_put_contents($db_file, json_encode($activeSessions));

$error = '';
$msg = isset($_GET['msg']) ? htmlspecialchars($_GET['msg']) : '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = htmlspecialchars($_POST['username'] ?? '');
    $password = htmlspecialchars($_POST['password'] ?? '');

    if ($username && $password) {
        // Check max 3 concurrent sessions for this user
        $userSessions = array_filter($activeSessions, function($s) use ($username) {
            return $s['username'] === $username;
        });

        if (count($userSessions) >= 3) {
            $error = "Maximum 3 concurrent sessions allowed. Please logout from another device.";
        } else {
            // Create session
            $_SESSION['username'] = $username;
            $_SESSION['login_time'] = time();

            // Add to active sessions
            $activeSessions[session_id()] = [
                'username' => $username,
                'timestamp' => time(),
                'ip' => $_SERVER['REMOTE_ADDR']
            ];

            file_put_contents($db_file, json_encode($activeSessions));

            header("Location: dashboard.php");
            exit();
        }
    } else {
        $error = "Please enter username and password";
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Login - Session Limited (Max 3 Sessions)</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; min-height: 100vh; display: flex; justify-content: center; align-items: center; padding: 20px; }
        .container { background: white; padding: 30px; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1); width: 100%; max-width: 400px; }
        h1 { text-align: center; color: #333; margin-bottom: 10px; }
        .subtitle { text-align: center; color: #666; font-size: 14px; margin-bottom: 30px; }
        label { display: block; margin: 15px 0 5px; color: #333; font-weight: bold; }
        input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; }
        input:focus { outline: none; border-color: #2196F3; background: #e3f2fd; }
        .btn { width: 100%; padding: 12px; background: #2196F3; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; margin-top: 20px; }
        .btn:hover { background: #1976D2; }
        .error { background: #f8d7da; color: #721c24; padding: 12px; border-radius: 4px; margin-bottom: 15px; border: 1px solid #f5c6cb; }
        .success { background: #d4edda; color: #155724; padding: 12px; border-radius: 4px; margin-bottom: 15px; border: 1px solid #c3e6cb; }
        .info { background: #e7f3ff; padding: 12px; border-radius: 4px; margin-top: 15px; border-left: 4px solid #0066cc; color: #333; font-size: 13px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔐 Login</h1>
        <p class="subtitle">Session Limited System (Max 3 Sessions, 5 Min Timeout)</p>

        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if ($msg): ?>
            <div class="success"><?php echo $msg; ?></div>
        <?php endif; ?>

        <form method="POST">
            <label>Username:</label>
            <input type="text" name="username" placeholder="Enter username" required>

            <label>Password:</label>
            <input type="password" name="password" placeholder="Enter password" required>

            <button type="submit" class="btn">Login</button>
        </form>

        <div class="info">
            <p><strong>Test Credentials:</strong></p>
            <p>Username: user1, user2, user3, user4</p>
            <p>Password: any password</p>
            <p style="margin-top: 10px;"><strong>Rules:</strong></p>
            <p>• Max 3 concurrent sessions per user</p>
            <p>• Session expires after 5 minutes</p>
            <p>• Login from 4th device will be blocked</p>
        </div>
    </div>
</body>
</html>
