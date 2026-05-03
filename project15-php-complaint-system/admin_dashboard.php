<?php
session_start();
require 'db.php';

if (!isset($_SESSION['admin_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: admin_login.php');
    exit();
}

$success = '';

// Handle status update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    $complaint_id = intval($_POST['complaint_id']);
    $new_status = htmlspecialchars($_POST['status']);

    $query = "UPDATE complaints SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('si', $new_status, $complaint_id);

    if ($stmt->execute()) {
        $success = 'Complaint status updated successfully!';
    }
    $stmt->close();
}

// Fetch all complaints with student details
$query = "SELECT c.*, u.username as student_name 
          FROM complaints c 
          JOIN users u ON c.student_id = u.id 
          ORDER BY c.created_at DESC";
$result = $conn->query($query);
$complaints = $result->fetch_all(MYSQLI_ASSOC);

// Statistics
$total = count($complaints);
$open = count(array_filter($complaints, fn($c) => $c['status'] == 'open'));
$inprogress = count(array_filter($complaints, fn($c) => $c['status'] == 'in-progress'));
$resolved = count(array_filter($complaints, fn($c) => $c['status'] == 'resolved'));
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - Complaint System</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; padding: 20px; }
        .container { max-width: 1200px; margin: 0 auto; background: white; padding: 25px; border-radius: 5px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #333; margin-bottom: 20px; border-bottom: 2px solid #d32f2f; padding-bottom: 10px; }
        .welcome { background: #ffebee; padding: 12px; border-radius: 3px; margin-bottom: 20px; }
        .logout-btn { background: #d32f2f; color: white; padding: 10px 20px; border: none; border-radius: 3px; cursor: pointer; float: right; }
        .logout-btn:hover { background: #b71c1c; }
        .stats { display: flex; gap: 10px; margin-bottom: 20px; }
        .stat-box { flex: 1; padding: 15px; text-align: center; color: white; border-radius: 3px; font-weight: bold; }
        .stat-total { background: #2196F3; }
        .stat-open { background: #f44336; }
        .stat-progress { background: #ff9800; }
        .stat-resolved { background: #4caf50; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #d32f2f; color: white; font-size: 14px; }
        tr:hover { background: #f5f5f5; }
        .status-open { color: #d32f2f; font-weight: bold; }
        .status-in-progress { color: #ff9800; font-weight: bold; }
        .status-resolved { color: #4caf50; font-weight: bold; }
        select { padding: 5px; border: 1px solid #ddd; border-radius: 3px; }
        .update-btn { padding: 5px 10px; background: #2196F3; color: white; border: none; border-radius: 3px; cursor: pointer; margin-left: 5px; }
        .update-btn:hover { background: #1976D2; }
        .success { background: #e8f5e9; color: #2e7d32; padding: 10px; margin-bottom: 15px; border-radius: 3px; }
        .desc-cell { max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    </style>
</head>
<body>
    <div class="container">
        <button class="logout-btn" onclick="window.location.href='logout.php'">Logout</button>
        <h1>👨‍💼 Admin Complaint Management</h1>

        <div class="welcome">
            Welcome, <strong><?php echo htmlspecialchars($_SESSION['admin_username']); ?></strong>
        </div>

        <?php if ($success): ?><div class="success"><?php echo $success; ?></div><?php endif; ?>

        <div class="stats">
            <div class="stat-box stat-total">Total: <?php echo $total; ?></div>
            <div class="stat-box stat-open">Open: <?php echo $open; ?></div>
            <div class="stat-box stat-progress">In Progress: <?php echo $inprogress; ?></div>
            <div class="stat-box stat-resolved">Resolved: <?php echo $resolved; ?></div>
        </div>

        <h2>All Student Complaints</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Student</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Description</th>
                    <th>Current Status</th>
                    <th>Update Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($complaints)): ?>
                    <tr><td colspan="8" style="text-align:center;">No complaints found</td></tr>
                <?php else: ?>
                    <?php foreach ($complaints as $complaint): ?>
                        <tr>
                            <td><?php echo $complaint['id']; ?></td>
                            <td><?php echo htmlspecialchars($complaint['student_name']); ?></td>
                            <td><?php echo htmlspecialchars($complaint['title']); ?></td>
                            <td><?php echo htmlspecialchars($complaint['category']); ?></td>
                            <td class="desc-cell" title="<?php echo htmlspecialchars($complaint['description']); ?>">
                                <?php echo htmlspecialchars(substr($complaint['description'], 0, 30)); ?>...
                            </td>
                            <td class="status-<?php echo $complaint['status']; ?>"><?php echo ucfirst($complaint['status']); ?></td>
                            <td>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="update_status" value="1">
                                    <input type="hidden" name="complaint_id" value="<?php echo $complaint['id']; ?>">
                                    <select name="status">
                                        <option value="open" <?php echo $complaint['status'] == 'open' ? 'selected' : ''; ?>>Open</option>
                                        <option value="in-progress" <?php echo $complaint['status'] == 'in-progress' ? 'selected' : ''; ?>>In Progress</option>
                                        <option value="resolved" <?php echo $complaint['status'] == 'resolved' ? 'selected' : ''; ?>>Resolved</option>
                                    </select>
                                    <button type="submit" class="update-btn">Update</button>
                                </form>
                            </td>
                            <td><?php echo date('d-M-Y', strtotime($complaint['created_at'])); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
