<?php
ini_set('session.gc_maxlifetime', 300); // 5 minutes - must be set before session_start()
session_start();

// Session timeout check
if (isset($_SESSION['login_time'])) {
    if (time() - $_SESSION['login_time'] > 300) {
        session_destroy();
        header("Location: login.php?msg=Session expired");
        exit();
    }
} else {
    $_SESSION['login_time'] = time();
}

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - Session Limited</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #e8f4f8; padding: 20px; }
        .container { max-width: 600px; margin: 50px auto; background: white; padding: 30px; border-radius: 5px; box-shadow: 0 0 15px rgba(0,0,0,0.1); }
        h1 { color: #333; margin-bottom: 20px; text-align: center; border-bottom: 2px solid #0066cc; padding-bottom: 10px; }
        .info { background: #e3f2fd; padding: 15px; border-left: 4px solid #0066cc; margin: 15px 0; border-radius: 3px; }
        .info p { margin: 8px 0; color: #333; }
        .logout-btn { display: inline-block; padding: 10px 20px; background: #d32f2f; color: white; text-decoration: none; border-radius: 3px; margin-top: 20px; cursor: pointer; border: none; font-weight: bold; }
        .logout-btn:hover { background: #b71c1c; }
        .timer { color: #d32f2f; font-weight: bold; margin-top: 15px; }
        .stats { background: #f5f5f5; padding: 15px; border-radius: 3px; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>✓ Dashboard</h1>
        
        <div class="info">
            <p><strong>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</strong></p>
            <p>Your session is active and will expire after 5 minutes of inactivity.</p>
        </div>

        <div class="stats">
            <p><strong>Session Info:</strong></p>
            <p>Session ID: <?php echo session_id(); ?></p>
            <p>Login Time: <?php echo date('Y-m-d H:i:s', $_SESSION['login_time']); ?></p>
            <p>Last Activity: <?php echo date('Y-m-d H:i:s'); ?></p>
        </div>

        <div class="timer">
            <p>Session will expire in: <span id="countdown">5:00</span></p>
        </div>

        <button class="logout-btn" onclick="logout()">Logout</button>
    </div>

    <script>
        let sessionTimeout = 300; // 5 minutes

        function updateCountdown() {
            let minutes = Math.floor(sessionTimeout / 60);
            let seconds = sessionTimeout % 60;
            document.getElementById('countdown').textContent = minutes + ':' + (seconds < 10 ? '0' : '') + seconds;
            
            if (sessionTimeout <= 0) {
                window.location.href = 'login.php?msg=Session expired';
            } else {
                sessionTimeout--;
                setTimeout(updateCountdown, 1000);
            }
        }

        function logout() {
            window.location.href = 'logout.php';
        }

        updateCountdown();

        // Reset timeout on any activity
        document.addEventListener('click', function() {
            fetch('refresh_session.php');
        });
    </script>
</body>
</html>
