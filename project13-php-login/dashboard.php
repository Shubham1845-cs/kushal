<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - Login Module</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; padding: 20px; }
        .container { max-width: 600px; margin: 50px auto; background: white; padding: 30px; border-radius: 5px; box-shadow: 0 0 15px rgba(0,0,0,0.1); }
        h1 { color: #333; margin-bottom: 20px; text-align: center; border-bottom: 2px solid #4caf50; padding-bottom: 10px; }
        .info { background: #e8f5e9; padding: 15px; border-left: 4px solid #4caf50; border-radius: 3px; margin: 15px 0; }
        .info p { margin: 8px 0; color: #333; }
        .logout-btn { display: inline-block; padding: 12px 25px; background: #d32f2f; color: white; text-decoration: none; border-radius: 3px; cursor: pointer; font-weight: bold; border: none; }
        .logout-btn:hover { background: #b71c1c; }
        .cookie-info { background: #fff3cd; padding: 12px; border-left: 4px solid #ffc107; border-radius: 3px; margin-top: 15px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>✓ Welcome to Dashboard</h1>

        <div class="info">
            <p><strong>Logged in as:</strong> <?php echo htmlspecialchars($_SESSION['username']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($_SESSION['email']); ?></p>
            <p><strong>User ID:</strong> <?php echo htmlspecialchars($_SESSION['user_id']); ?></p>
        </div>

        <div class="cookie-info">
            <p><strong>💾 Cookie Information:</strong></p>
            <p>If you checked "Remember me", your username is saved in a cookie for 30 days.</p>
            <?php if (isset($_COOKIE['remember_user'])): ?>
                <p style="color: #d32f2f;">Remember-me cookie is active for: <strong><?php echo htmlspecialchars($_COOKIE['remember_user']); ?></strong></p>
            <?php else: ?>
                <p>No remember-me cookie found.</p>
            <?php endif; ?>
        </div>

        <button class="logout-btn" onclick="logout()">Logout</button>
    </div>

    <script>
        function logout() {
            if (confirm('Are you sure you want to logout?')) {
                window.location.href = 'logout.php';
            }
        }
    </script>
</body>
</html>
