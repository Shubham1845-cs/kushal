<?php
session_start();

$getOutput = '';
$postOutput = '';
$loginOutput = '';
$registerOutput = '';

// --- Simple users table + hashed passwords (uses local MySQL via XAMPP) ---
$auth = new mysqli('localhost', 'root', '');
if ($auth->connect_error) {
  // fail silently for environments without MySQL; login will fall back to demo if needed
  $auth = null;
} else {
  $auth->query('CREATE DATABASE IF NOT EXISTS wtlab_auth');
  $auth->select_db('wtlab_auth');
  $auth->query('CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE,
    password_hash VARCHAR(255) NOT NULL
  )');

  // seed default admin user if table empty
  $res = $auth->query('SELECT COUNT(*) AS c FROM users');
  if ($res) {
    $row = $res->fetch_assoc();
    if ((int)$row['c'] === 0) {
      $defaultUser = 'admin';
      $defaultPass = password_hash('admin123', PASSWORD_DEFAULT);
      $stmt = $auth->prepare('INSERT INTO users (username, password_hash) VALUES (?, ?)');
      if ($stmt) {
        $stmt->bind_param('ss', $defaultUser, $defaultPass);
        $stmt->execute();
        $stmt->close();
      }
    }
  }
}

if (isset($_GET['submit_get'])) {
    $getName = htmlspecialchars($_GET['get_name'] ?? '');
    $getEmail = htmlspecialchars($_GET['get_email'] ?? '');
    $getOutput = "GET Data: Name = {$getName}, Email = {$getEmail}";
}

if (isset($_POST['submit_post'])) {
    $postName = trim($_POST['post_name'] ?? '');
    $postEmail = trim($_POST['post_email'] ?? '');
    $postPassword = $_POST['post_password'] ?? '';

    if (!filter_var($postEmail, FILTER_VALIDATE_EMAIL)) {
        $postOutput = 'Invalid email format.';
    } else {
        setcookie('username', $postName, time() + 86400, '/');
        $safeName = htmlspecialchars($postName);
        $safeEmail = htmlspecialchars($postEmail);
        $postOutput = "POST Data: Name = {$safeName}, Email = {$safeEmail}. Cookie created for username.";
    }
}

if (isset($_POST['login_submit'])) {
    $loginUser = trim($_POST['login_username'] ?? '');
    $loginPass = trim($_POST['login_password'] ?? '');

  // If DB connection available, validate against users table
  if ($auth) {
    $stmt = $auth->prepare('SELECT id, password_hash FROM users WHERE username = ? LIMIT 1');
    if ($stmt) {
      $stmt->bind_param('s', $loginUser);
      $stmt->execute();
      $result = $stmt->get_result();
      if ($row = $result->fetch_assoc()) {
        if (password_verify($loginPass, $row['password_hash'])) {
          $_SESSION['username'] = $loginUser;
          $loginOutput = 'Login successful. Session started.';
        } else {
          $loginOutput = 'Invalid login credentials.';
        }
      } else {
        $loginOutput = 'Invalid login credentials.';
      }
      $stmt->close();
    } else {
      $loginOutput = 'Login error.';
    }
  } else {
    // fallback to demo credentials only if DB not available
    if ($loginUser === 'admin' && $loginPass === 'admin123') {
      $_SESSION['username'] = $loginUser;
      $loginOutput = 'Login successful (demo). Session started.';
    } else {
      $loginOutput = 'Invalid login credentials.';
    }
  }
}

// Registration handler
if (isset($_POST['register_submit'])) {
    $regUser = trim($_POST['reg_username'] ?? '');
    $regPass = trim($_POST['reg_password'] ?? '');
    $regPass2 = trim($_POST['reg_password2'] ?? '');

    if ($regUser === '' || $regPass === '') {
        $registerOutput = 'Username and password are required.';
    } elseif ($regPass !== $regPass2) {
        $registerOutput = 'Passwords do not match.';
    } elseif (!$auth) {
        $registerOutput = 'Registration unavailable (no DB connection).';
    } else {
        // check username exists
        $chk = $auth->prepare('SELECT id FROM users WHERE username = ? LIMIT 1');
        $chk->bind_param('s', $regUser);
        $chk->execute();
        $res = $chk->get_result();
        if ($res && $res->fetch_assoc()) {
            $registerOutput = 'Username already exists.';
        } else {
            $hash = password_hash($regPass, PASSWORD_DEFAULT);
            $ins = $auth->prepare('INSERT INTO users (username, password_hash) VALUES (?, ?)');
            if ($ins) {
                $ins->bind_param('ss', $regUser, $hash);
                $ins->execute();
                $ins->close();
                $registerOutput = 'User registered successfully.';
            } else {
                $registerOutput = 'Registration error.';
            }
        }
        $chk->close();
    }
}

if (isset($_POST['logout_submit'])) {
    session_unset();
    session_destroy();
    header('Location: index.php');
    exit;
}

$cookieUser = $_COOKIE['username'] ?? 'Not set';
$sessionUser = $_SESSION['username'] ?? 'Guest';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Problem 4 - Form and Session</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>
  <div class="container">
    <h1>Form Processing and Sessions</h1>
    <p class="subtitle">Simple GET, POST, cookie, and session examples.</p>

    <div class="card">
      <h2>1. GET Form</h2>
      <form method="get">
        <input type="text" name="get_name" placeholder="Name" required />
        <input type="email" name="get_email" placeholder="Email" required />
        <button type="submit" name="submit_get">Process GET</button>
      </form>
      <div class="message"><?php echo $getOutput; ?></div>
    </div>

    <div class="card">
      <h2>2. POST Form + Email Validation + Cookie</h2>
      <form method="post">
        <input type="text" name="post_name" placeholder="Name" required />
        <input type="email" name="post_email" placeholder="Email" required />
        <input type="password" name="post_password" placeholder="Password" required />
        <button type="submit" name="submit_post">Process POST</button>
      </form>
      <div class="message"><?php echo $postOutput; ?></div>
      <p>Cookie Username: <?php echo htmlspecialchars($cookieUser); ?></p>
    </div>

    <div class="card">
      <h2>3. Session Login Example</h2>
      <form method="post">
        <input type="text" name="login_username" placeholder="Username" required />
        <input type="password" name="login_password" placeholder="Password" required />
        <button type="submit" name="login_submit">Login</button>
      </form>
      <div class="message"><?php echo $loginOutput; ?></div>
      <p>Current Session User: <?php echo htmlspecialchars($sessionUser); ?></p>
      <form method="post">
        <button type="submit" name="logout_submit">Logout</button>
      </form>
      <p class="hint">Demo credentials: admin / admin123</p>
    </div>

    <div class="card">
      <h2>4. Register User</h2>
      <form method="post">
        <input type="text" name="reg_username" placeholder="Username" required />
        <input type="password" name="reg_password" placeholder="Password" required />
        <input type="password" name="reg_password2" placeholder="Confirm Password" required />
        <button type="submit" name="register_submit">Register</button>
      </form>
      <div class="message"><?php echo $registerOutput; ?></div>
    </div>

  </div>
</body>
</html>
