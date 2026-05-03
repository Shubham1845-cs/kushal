<?php
session_start();

$host = 'localhost';
$user = 'root';
$password = '';
$database = 'login_db';

$connection = mysqli_connect($host, $user, $password, $database);

if (!$connection) {
    die("Connection failed");
}

$error = '';
$username_filled = '';

// Check if remember-me cookie exists
if (isset($_COOKIE['remember_user'])) {
    $username_filled = htmlspecialchars($_COOKIE['remember_user']);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = htmlspecialchars($_POST['username']);
    $pass = htmlspecialchars($_POST['password']);
    $remember = isset($_POST['remember']) ? true : false;

    if ($username && $pass) {
        $query = "SELECT * FROM users WHERE username = ?";
        $stmt = $connection->prepare($query);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($pass, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];

                if ($remember) {
                    setcookie('remember_user', $username, time() + (86400 * 30), '/'); // 30 days
                } else {
                    setcookie('remember_user', '', time() - 3600, '/');
                }

                header("Location: dashboard.php");
                exit();
            } else {
                $error = "Invalid password!";
            }
        } else {
            $error = "User not found!";
        }
        $stmt->close();
    } else {
        $error = "Please enter username and password";
    }
}

mysqli_close($connection);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login - Login Module</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; }
        .container { max-width: 400px; margin: 50px auto; background: white; padding: 30px; border-radius: 5px; box-shadow: 0 0 15px rgba(0,0,0,0.1); }
        h1 { text-align: center; color: #333; margin-bottom: 10px; }
        .subtitle { text-align: center; color: #666; font-size: 14px; margin-bottom: 20px; }
        label { display: block; margin: 12px 0 5px; font-weight: bold; color: #333; }
        input[type="text"], input[type="password"] { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 3px; margin-bottom: 10px; }
        input:focus { outline: none; border-color: #2196F3; background: #e3f2fd; }
        .remember { display: flex; align-items: center; margin: 15px 0; }
        input[type="checkbox"] { width: 18px; height: 18px; margin-right: 8px; cursor: pointer; }
        .remember label { margin: 0; display: inline; font-weight: normal; }
        button { width: 100%; padding: 12px; background: #4caf50; color: white; border: none; border-radius: 3px; cursor: pointer; font-weight: bold; margin-top: 10px; }
        button:hover { background: #45a049; }
        .error { background: #ffebee; color: #c62828; padding: 12px; border-radius: 3px; margin-bottom: 15px; border-left: 4px solid #d32f2f; }
        .link { text-align: center; margin-top: 15px; }
        .link a { color: #2196F3; text-decoration: none; }
        .link a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔐 Login</h1>
        <p class="subtitle">Sign in to your account</p>

        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <label>Username:</label>
            <input type="text" name="username" placeholder="Enter username" value="<?php echo $username_filled; ?>" required>

            <label>Password:</label>
            <input type="password" name="password" placeholder="Enter password" required>

            <div class="remember">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">Remember me for 30 days</label>
            </div>

            <button type="submit">Login</button>
        </form>

        <div class="link">
            Don't have an account? <a href="register.php">Register here</a>
        </div>
    </div>
</body>
</html>
