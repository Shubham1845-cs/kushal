<?php
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'login_db';

$connection = mysqli_connect($host, $user, $password, $database);

if (!$connection) {
    die("Connection failed");
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $pass = htmlspecialchars($_POST['password']);
    $pass_confirm = htmlspecialchars($_POST['password_confirm']);

    if ($username && $email && $pass) {
        if ($pass !== $pass_confirm) {
            $error = "Passwords do not match!";
        } else if (strlen($pass) < 5) {
            $error = "Password must be at least 5 characters!";
        } else {
            $pass_hashed = password_hash($pass, PASSWORD_BCRYPT);
            $query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
            $stmt = $connection->prepare($query);
            $stmt->bind_param('sss', $username, $email, $pass_hashed);

            if ($stmt->execute()) {
                $success = "Registration successful! <a href='login.php'>Login here</a>";
                $_POST = [];
            } else {
                if (strpos($stmt->error, 'Duplicate') !== false) {
                    $error = "Username or email already exists!";
                } else {
                    $error = $stmt->error;
                }
            }
            $stmt->close();
        }
    } else {
        $error = "All fields are required!";
    }
}

mysqli_close($connection);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register - Login Module</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; }
        .container { max-width: 400px; margin: 50px auto; background: white; padding: 30px; border-radius: 5px; box-shadow: 0 0 15px rgba(0,0,0,0.1); }
        h1 { text-align: center; color: #333; margin-bottom: 10px; }
        .subtitle { text-align: center; color: #666; font-size: 14px; margin-bottom: 20px; }
        label { display: block; margin: 12px 0 5px; font-weight: bold; color: #333; }
        input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 3px; margin-bottom: 10px; }
        input:focus { outline: none; border-color: #2196F3; background: #e3f2fd; }
        button { width: 100%; padding: 12px; background: #2196F3; color: white; border: none; border-radius: 3px; cursor: pointer; font-weight: bold; margin-top: 10px; }
        button:hover { background: #1976D2; }
        .error { background: #ffebee; color: #c62828; padding: 12px; border-radius: 3px; margin-bottom: 15px; border-left: 4px solid #d32f2f; }
        .success { background: #e8f5e9; color: #2e7d32; padding: 12px; border-radius: 3px; margin-bottom: 15px; border-left: 4px solid #4caf50; }
        .success a { color: #2e7d32; font-weight: bold; text-decoration: none; }
        .link { text-align: center; margin-top: 15px; }
        .link a { color: #2196F3; text-decoration: none; }
        .link a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container">
        <h1>📝 Register</h1>
        <p class="subtitle">Create a new account</p>

        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>

        <form method="POST">
            <label>Username:</label>
            <input type="text" name="username" placeholder="Choose username" required>

            <label>Email:</label>
            <input type="email" name="email" placeholder="Enter email" required>

            <label>Password:</label>
            <input type="password" name="password" placeholder="Password (min 5 chars)" required>

            <label>Confirm Password:</label>
            <input type="password" name="password_confirm" placeholder="Confirm password" required>

            <button type="submit">Register</button>
        </form>

        <div class="link">
            Already have an account? <a href="login.php">Login here</a>
        </div>
    </div>
</body>
</html>
