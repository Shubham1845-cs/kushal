<?php
require 'db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);
    $password_confirm = htmlspecialchars($_POST['password_confirm']);

    if ($username && $password) {
        if ($password !== $password_confirm) {
            $error = 'Passwords do not match';
        } else if (strlen($password) < 5) {
            $error = 'Password must be at least 5 characters';
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $query = "INSERT INTO users (username, password, role) VALUES (?, ?, 'student')";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('ss', $username, $hashedPassword);

            if ($stmt->execute()) {
                $success = 'Student registered successfully! <a href="student_login.php">Login now</a>';
            } else {
                if (strpos($stmt->error, 'Duplicate') !== false) {
                    $error = 'Username already exists';
                } else {
                    $error = $stmt->error;
                }
            }
            $stmt->close();
        }
    } else {
        $error = 'All fields are required';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Student Registration - Complaint System</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; }
        .container { max-width: 400px; margin: 50px auto; background: white; padding: 30px; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h1 { text-align: center; color: #333; margin-bottom: 20px; }
        label { display: block; margin: 10px 0 5px; font-weight: bold; }
        input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 3px; margin-bottom: 10px; }
        button { width: 100%; padding: 10px; background: #4caf50; color: white; border: none; border-radius: 3px; cursor: pointer; font-weight: bold; }
        button:hover { background: #45a049; }
        .error { background: #ffebee; color: #c62828; padding: 10px; margin-bottom: 15px; border-radius: 3px; }
        .success { background: #e8f5e9; color: #2e7d32; padding: 10px; margin-bottom: 15px; border-radius: 3px; }
        .success a { color: #2e7d32; font-weight: bold; }
        .links { text-align: center; margin-top: 15px; }
        .links a { color: #2196F3; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <h1>📝 Student Registration</h1>

        <?php if ($error): ?><div class="error"><?php echo $error; ?></div><?php endif; ?>
        <?php if ($success): ?><div class="success"><?php echo $success; ?></div><?php endif; ?>

        <form method="POST">
            <label>Username:</label>
            <input type="text" name="username" required>

            <label>Password:</label>
            <input type="password" name="password" required>

            <label>Confirm Password:</label>
            <input type="password" name="password_confirm" required>

            <button type="submit">Register as Student</button>
        </form>

        <div class="links">
            <p><a href="student_login.php">Already have account? Login</a></p>
        </div>
    </div>
</body>
</html>
