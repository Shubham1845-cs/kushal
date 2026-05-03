<?php
session_start();
require 'db.php';

if (!isset($_SESSION['student_id']) || $_SESSION['role'] !== 'student') {
    header('Location: student_login.php');
    exit();
}

$error = '';
$success = '';

// Handle complaint submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_complaint'])) {
    $title = htmlspecialchars($_POST['title']);
    $description = htmlspecialchars($_POST['description']);
    $category = htmlspecialchars($_POST['category']);
    $student_id = $_SESSION['student_id'];

    if ($title && $description && $category) {
        $query = "INSERT INTO complaints (student_id, title, description, category) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('isss', $student_id, $title, $description, $category);

        if ($stmt->execute()) {
            $success = 'Complaint submitted successfully!';
        } else {
            $error = 'Error submitting complaint';
        }
        $stmt->close();
    } else {
        $error = 'All fields are required';
    }
}

// Fetch student's complaints
$student_id = $_SESSION['student_id'];
$query = "SELECT * FROM complaints WHERE student_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $student_id);
$stmt->execute();
$result = $stmt->get_result();
$complaints = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Student Dashboard - Complaint System</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; padding: 20px; }
        .container { max-width: 1000px; margin: 0 auto; background: white; padding: 25px; border-radius: 5px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #333; margin-bottom: 20px; border-bottom: 2px solid #2196F3; padding-bottom: 10px; }
        .welcome { background: #e3f2fd; padding: 12px; border-radius: 3px; margin-bottom: 20px; }
        .form-section { background: #f9f9f9; padding: 20px; border-radius: 5px; margin-bottom: 25px; border: 1px solid #ddd; }
        label { display: block; margin: 10px 0 5px; font-weight: bold; }
        input, textarea, select { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 3px; margin-bottom: 10px; }
        button { padding: 10px 20px; background: #2196F3; color: white; border: none; border-radius: 3px; cursor: pointer; font-weight: bold; }
        button:hover { background: #1976D2; }
        .logout-btn { background: #d32f2f; float: right; }
        .logout-btn:hover { background: #b71c1c; }
        .error { background: #ffebee; color: #c62828; padding: 10px; margin-bottom: 15px; border-radius: 3px; }
        .success { background: #e8f5e9; color: #2e7d32; padding: 10px; margin-bottom: 15px; border-radius: 3px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #2196F3; color: white; }
        .status-open { color: #d32f2f; font-weight: bold; }
        .status-in-progress { color: #ff9800; font-weight: bold; }
        .status-resolved { color: #4caf50; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <button class="logout-btn" onclick="window.location.href='logout.php'">Logout</button>
        <h1>🎓 Student Complaint Dashboard</h1>

        <div class="welcome">
            Welcome, <strong><?php echo htmlspecialchars($_SESSION['student_username']); ?></strong>
        </div>

        <?php if ($error): ?><div class="error"><?php echo $error; ?></div><?php endif; ?>
        <?php if ($success): ?><div class="success"><?php echo $success; ?></div><?php endif; ?>

        <div class="form-section">
            <h2>Submit New Complaint</h2>
            <form method="POST">
                <input type="hidden" name="submit_complaint" value="1">

                <label>Complaint Title:</label>
                <input type="text" name="title" placeholder="Enter complaint title" required>

                <label>Category:</label>
                <select name="category" required>
                    <option value="">Select category</option>
                    <option value="Academic">Academic</option>
                    <option value="Hostel">Hostel</option>
                    <option value="Library">Library</option>
                    <option value="Infrastructure">Infrastructure</option>
                    <option value="Other">Other</option>
                </select>

                <label>Description:</label>
                <textarea name="description" rows="4" placeholder="Describe your complaint" required></textarea>

                <button type="submit">Submit Complaint</button>
            </form>
        </div>

        <h2>My Complaints</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Submitted Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($complaints)): ?>
                    <tr><td colspan="6" style="text-align:center;">No complaints submitted yet</td></tr>
                <?php else: ?>
                    <?php foreach ($complaints as $complaint): ?>
                        <tr>
                            <td><?php echo $complaint['id']; ?></td>
                            <td><?php echo htmlspecialchars($complaint['title']); ?></td>
                            <td><?php echo htmlspecialchars($complaint['category']); ?></td>
                            <td><?php echo htmlspecialchars(substr($complaint['description'], 0, 50)); ?>...</td>
                            <td class="status-<?php echo $complaint['status']; ?>"><?php echo ucfirst($complaint['status']); ?></td>
                            <td><?php echo date('d-M-Y', strtotime($complaint['created_at'])); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
