<?php
require 'db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $roll_no = htmlspecialchars($_POST['roll_no']);
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);

    if ($roll_no && $name && $email) {
        $query = "INSERT INTO students (roll_no, name, email) VALUES (?, ?, ?)";
        $stmt = $connection->prepare($query);
        $stmt->bind_param('sss', $roll_no, $name, $email);

        if ($stmt->execute()) {
            $success = "Student registered successfully!";
            $_POST = [];
        } else {
            if (strpos($stmt->error, 'Duplicate') !== false) {
                $error = "Roll number already exists!";
            } else {
                $error = $stmt->error;
            }
        }
        $stmt->close();
    } else {
        $error = "All fields are required!";
    }
}

$query = "SELECT * FROM students ORDER BY roll_no";
$result = $connection->query($query);
$students = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Student Registration - Attendance System</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; padding: 20px; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 25px; border-radius: 5px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #333; margin-bottom: 25px; border-bottom: 2px solid #2196F3; padding-bottom: 10px; }
        .form-section { background: #f9f9f9; padding: 20px; border-radius: 5px; margin-bottom: 25px; border: 1px solid #ddd; }
        label { display: block; margin: 10px 0 5px; font-weight: bold; color: #333; }
        input { width: 100%; padding: 10px; margin-bottom: 12px; border: 1px solid #ddd; border-radius: 3px; }
        input:focus { outline: none; border-color: #2196F3; background: #e3f2fd; }
        button { padding: 10px 20px; background: #2196F3; color: white; border: none; border-radius: 3px; cursor: pointer; font-weight: bold; margin-right: 5px; }
        button:hover { background: #1976D2; }
        .error { background: #ffebee; color: #c62828; padding: 12px; border-radius: 3px; margin-bottom: 15px; border-left: 4px solid #d32f2f; }
        .success { background: #e8f5e9; color: #2e7d32; padding: 12px; border-radius: 3px; margin-bottom: 15px; border-left: 4px solid #4caf50; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #2196F3; color: white; }
        tr:hover { background: #f5f5f5; }
    </style>
</head>
<body>
    <div class="container">
        <h1>📚 Student Registration</h1>

        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>

        <div class="form-section">
            <h2>Register New Student</h2>
            <form method="POST">
                <label>Roll Number:</label>
                <input type="text" name="roll_no" placeholder="e.g., 001" required>

                <label>Full Name:</label>
                <input type="text" name="name" placeholder="e.g., Rahul Kumar" required>

                <label>Email:</label>
                <input type="email" name="email" placeholder="e.g., rahul@example.com" required>

                <button type="submit">Register Student</button>
                <button type="reset">Clear</button>
            </form>
        </div>

        <h2>Registered Students</h2>
        <table>
            <thead>
                <tr>
                    <th>Roll No</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Registered Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($students)): ?>
                    <tr><td colspan="4" style="text-align: center;">No students registered yet</td></tr>
                <?php else: ?>
                    <?php foreach ($students as $student): ?>
                        <tr>
                            <td><strong><?php echo $student['roll_no']; ?></strong></td>
                            <td><?php echo $student['name']; ?></td>
                            <td><?php echo $student['email']; ?></td>
                            <td><?php echo date('d-M-Y', strtotime($student['created_at'])); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
