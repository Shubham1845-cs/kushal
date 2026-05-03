<?php
session_start();
require 'db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);

    if ($username && $password) {
        $query = "SELECT * FROM users WHERE username = ? AND role = 'admin'";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $admin = $result->fetch_assoc();
            if (password_verify($password, $admin['password'])) {
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_username'] = $admin['username'];
                $_SESSION['role'] = 'admin';
                header('Location: admin_dashboard.php');
                exit();
            } else {
                $error = 'Invalid password';
            }
        } else {
            $error = 'Admin user not found';
        }
        $stmt->close();
    } else {
        $error = 'Please enter username and password';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Login - Complaint System</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; }
        .container { max-width: 400px; margin: 50px auto; background: white; padding: 30px; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h1 { text-align: center; color: #333; margin-bottom: 20px; }
        label { display: block; margin: 10px 0 5px; font-weight: bold; }
        input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 3px; margin-bottom: 10px; }
        button { width: 100%; padding: 10px; background: #d32f2f; color: white; border: none; border-radius: 3px; cursor: pointer; font-weight: bold; }
        button:hover { background: #b71c1c; }
        .error { background: #ffebee; color: #c62828; padding: 10px; margin-bottom: 15px; border-radius: 3px; }
        .links { text-align: center; margin-top: 15px; }
        .links a { color: #2196F3; text-decoration: none; }
        .credentials { background: #fff3cd; padding: 10px; margin-top: 15px; border-radius: 3px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>👨‍💼 Admin Login</h1>

        <?php if ($error): ?><div class="error"><?php echo $error; ?></div><?php endif; ?>

        <form method="POST">
            <label>Admin Username:</label>
            <input type="text" name="username" required>

            <label>Password:</label>
            <input type="password" name="password" required>

            <button type="submit">Login as Admin</button>
        </form>

        <div class="credentials">
            <strong>Default Admin:</strong><br>
            Username: admin<br>
            Password: admin123
        </div>

        <div class="links">
            <p><a href="student_login.php">Student Login</a></p>
        </div>
    </div>
</body>
</html>
