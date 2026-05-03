<?php
require 'db.php';

$success = '';
$error = '';

// Handle status update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    $id     = intval($_POST['id']);
    $status = htmlspecialchars($_POST['status']);

    $allowed = ['pending', 'assigned', 'collected'];
    if (in_array($status, $allowed)) {
        $stmt = mysqli_prepare($conn, "UPDATE waste_requests SET status = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt, 'si', $status, $id);
        if (mysqli_stmt_execute($stmt)) {
            $success = "Request #$id status updated to: " . ucfirst($status);
        } else {
            $error = 'Failed to update status.';
        }
        mysqli_stmt_close($stmt);
    }
}

// Handle delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM waste_requests WHERE id = $id");
    header("Location: authority.php");
    exit();
}

// Stats
$total     = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM waste_requests"))['c'];
$pending   = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM waste_requests WHERE status='pending'"))['c'];
$assigned  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM waste_requests WHERE status='assigned'"))['c'];
$collected = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM waste_requests WHERE status='collected'"))['c'];

// Filter
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
$where  = ($filter !== 'all') ? "WHERE status = '" . mysqli_real_escape_string($conn, $filter) . "'" : '';
$requests = mysqli_query($conn, "SELECT * FROM waste_requests $where ORDER BY submitted_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authority Panel - Waste Management</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f0f0f0; padding: 20px; }
        .container { max-width: 1000px; margin: 0 auto; }
        h1 { text-align: center; color: #333; margin-bottom: 5px; }
        .subtitle { text-align: center; color: #666; margin-bottom: 20px; font-size: 14px; }
        .card { background: white; padding: 25px; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1); margin-bottom: 25px; }
        h2 { color: #333; margin-bottom: 15px; border-bottom: 2px solid #1565c0; padding-bottom: 8px; }

        /* Stats */
        .stats { display: flex; gap: 15px; flex-wrap: wrap; margin-bottom: 25px; }
        .stat { flex: 1; min-width: 120px; padding: 15px; border-radius: 5px; text-align: center; color: white; }
        .stat-total    { background: #1565c0; }
        .stat-pending  { background: #e65100; }
        .stat-assigned { background: #1565c0; }
        .stat-collected{ background: #2e7d32; }
        .stat h3 { font-size: 28px; }
        .stat p  { font-size: 13px; margin-top: 4px; }

        /* Filter tabs */
        .filters { display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 15px; }
        .filter-btn { padding: 7px 16px; border: 1px solid #ddd; border-radius: 3px; background: white; cursor: pointer; font-size: 13px; text-decoration: none; color: #333; }
        .filter-btn.active { background: #1565c0; color: white; border-color: #1565c0; }

        /* Table */
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; font-size: 13px; min-width: 700px; }
        th, td { padding: 10px 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #1565c0; color: white; }
        tr:hover { background: #f5f5f5; }

        /* Badge */
        .badge { padding: 3px 8px; border-radius: 3px; font-size: 12px; font-weight: bold; }
        .pending   { background: #fff3e0; color: #e65100; }
        .assigned  { background: #e3f2fd; color: #1565c0; }
        .collected { background: #e8f5e9; color: #2e7d32; }

        /* Status form */
        .status-form { display: flex; gap: 6px; align-items: center; }
        .status-form select { padding: 5px 8px; border: 1px solid #ddd; border-radius: 3px; font-size: 12px; }
        .btn-update { padding: 5px 10px; background: #1565c0; color: white; border: none; border-radius: 3px; cursor: pointer; font-size: 12px; }
        .btn-update:hover { background: #0d47a1; }
        .btn-delete { padding: 5px 10px; background: #c62828; color: white; border: none; border-radius: 3px; cursor: pointer; font-size: 12px; }
        .btn-delete:hover { background: #b71c1c; }

        .success { background: #e8f5e9; color: #2e7d32; padding: 12px; border-radius: 3px; margin-bottom: 15px; border-left: 4px solid #4caf50; }
        .error   { background: #ffebee; color: #c62828; padding: 12px; border-radius: 3px; margin-bottom: 15px; border-left: 4px solid #d32f2f; }

        .nav-link { display: inline-block; margin-bottom: 15px; color: #1565c0; text-decoration: none; font-weight: bold; font-size: 14px; }
        .nav-link:hover { text-decoration: underline; }
    </style>
</head>
<body>
<div class="container">
    <a class="nav-link" href="index.php">← Back to Public Form</a>
    <h1>Authority Panel</h1>
    <p class="subtitle">Manage and update waste collection requests</p>

    <?php if ($success): ?><div class="success"><?php echo $success; ?></div><?php endif; ?>
    <?php if ($error):   ?><div class="error"><?php echo $error; ?></div><?php endif; ?>

    <!-- Stats -->
    <div class="stats">
        <div class="stat stat-total">
            <h3><?php echo $total; ?></h3>
            <p>Total Requests</p>
        </div>
        <div class="stat stat-pending">
            <h3><?php echo $pending; ?></h3>
            <p>Pending</p>
        </div>
        <div class="stat stat-assigned">
            <h3><?php echo $assigned; ?></h3>
            <p>Assigned</p>
        </div>
        <div class="stat stat-collected">
            <h3><?php echo $collected; ?></h3>
            <p>Collected</p>
        </div>
    </div>

    <div class="card">
        <h2>Waste Collection Requests</h2>

        <!-- Filter -->
        <div class="filters">
            <a href="?filter=all"       class="filter-btn <?php echo $filter=='all'       ? 'active' : ''; ?>">All (<?php echo $total; ?>)</a>
            <a href="?filter=pending"   class="filter-btn <?php echo $filter=='pending'   ? 'active' : ''; ?>">Pending (<?php echo $pending; ?>)</a>
            <a href="?filter=assigned"  class="filter-btn <?php echo $filter=='assigned'  ? 'active' : ''; ?>">Assigned (<?php echo $assigned; ?>)</a>
            <a href="?filter=collected" class="filter-btn <?php echo $filter=='collected' ? 'active' : ''; ?>">Collected (<?php echo $collected; ?>)</a>
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Waste Type</th>
                        <th>Location</th>
                        <th>Description</th>
                        <th>Contact</th>
                        <th>Phone</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($requests) == 0): ?>
                        <tr><td colspan="9" style="text-align:center;padding:20px;color:#888;">No requests found</td></tr>
                    <?php else: ?>
                        <?php while ($row = mysqli_fetch_assoc($requests)): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['waste_type']; ?></td>
                                <td><?php echo $row['location']; ?></td>
                                <td><?php echo $row['description'] ?: '-'; ?></td>
                                <td><?php echo $row['contact_name']; ?></td>
                                <td><?php echo $row['contact_phone']; ?></td>
                                <td><?php echo date('d M Y', strtotime($row['submitted_at'])); ?></td>
                                <td><span class="badge <?php echo $row['status']; ?>"><?php echo ucfirst($row['status']); ?></span></td>
                                <td>
                                    <form method="POST" class="status-form">
                                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                        <select name="status">
                                            <option value="pending"   <?php echo $row['status']=='pending'   ? 'selected' : ''; ?>>Pending</option>
                                            <option value="assigned"  <?php echo $row['status']=='assigned'  ? 'selected' : ''; ?>>Assigned</option>
                                            <option value="collected" <?php echo $row['status']=='collected' ? 'selected' : ''; ?>>Collected</option>
                                        </select>
                                        <button type="submit" name="update_status" class="btn-update">Update</button>
                                    </form>
                                    <a href="?delete=<?php echo $row['id']; ?>" onclick="return confirm('Delete this request?')" style="margin-top:4px;display:inline-block;">
                                        <button class="btn-delete">Delete</button>
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
