<?php
require 'db.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $waste_type    = htmlspecialchars(trim($_POST['waste_type']));
    $location      = htmlspecialchars(trim($_POST['location']));
    $description   = htmlspecialchars(trim($_POST['description']));
    $contact_name  = htmlspecialchars(trim($_POST['contact_name']));
    $contact_phone = htmlspecialchars(trim($_POST['contact_phone']));

    if (empty($waste_type) || empty($location) || empty($contact_name) || empty($contact_phone)) {
        $error = 'Please fill in all required fields.';
    } else {
        $stmt = mysqli_prepare($conn, "INSERT INTO waste_requests (waste_type, location, description, contact_name, contact_phone) VALUES (?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, 'sssss', $waste_type, $location, $description, $contact_name, $contact_phone);
        if (mysqli_stmt_execute($stmt)) {
            $success = 'Waste collection request submitted successfully! Concerned authority has been notified.';
        } else {
            $error = 'Failed to submit request. Please try again.';
        }
        mysqli_stmt_close($stmt);
    }
}

// Fetch all requests
$requests = mysqli_query($conn, "SELECT * FROM waste_requests ORDER BY submitted_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Waste Collection System</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f0f0f0; padding: 20px; }
        .container { max-width: 900px; margin: 0 auto; }
        h1 { text-align: center; color: #333; margin-bottom: 20px; }
        .card { background: white; padding: 25px; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1); margin-bottom: 25px; }
        h2 { color: #333; margin-bottom: 15px; border-bottom: 2px solid #4caf50; padding-bottom: 8px; }
        label { display: block; font-weight: bold; color: #333; margin-bottom: 4px; margin-top: 10px; }
        input, select, textarea { width: 100%; padding: 9px; border: 1px solid #ddd; border-radius: 3px; font-size: 14px; }
        textarea { height: 80px; resize: vertical; }
        button { margin-top: 15px; padding: 10px 20px; background: #4caf50; color: white; border: none; border-radius: 3px; cursor: pointer; font-weight: bold; font-size: 14px; }
        button:hover { background: #388e3c; }
        .success { background: #e8f5e9; color: #2e7d32; padding: 12px; border-radius: 3px; margin-bottom: 15px; border-left: 4px solid #4caf50; }
        .error { background: #ffebee; color: #c62828; padding: 12px; border-radius: 3px; margin-bottom: 15px; border-left: 4px solid #d32f2f; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px 12px; text-align: left; border-bottom: 1px solid #ddd; font-size: 14px; }
        th { background: #4caf50; color: white; }
        tr:hover { background: #f9f9f9; }
        .badge { padding: 3px 8px; border-radius: 3px; font-size: 12px; font-weight: bold; }
        .pending  { background: #fff3e0; color: #e65100; }
        .assigned { background: #e3f2fd; color: #1565c0; }
        .collected{ background: #e8f5e9; color: #2e7d32; }
    </style>
</head>
<body>
<div class="container">
    <h1>♻️ Waste Collection System</h1>
    <div style="text-align:right;margin-bottom:10px;">
        <a href="authority.php" style="padding:8px 16px;background:#1565c0;color:white;border-radius:3px;text-decoration:none;font-weight:bold;font-size:14px;">Authority Panel</a>
    </div>

    <div class="card">
        <h2>Submit Waste Collection Request</h2>

        <?php if ($success): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <label>Waste Type *</label>
            <select name="waste_type" required>
                <option value="">-- Select Waste Type --</option>
                <option value="Plastic">Plastic</option>
                <option value="Paper">Paper</option>
                <option value="Glass">Glass</option>
                <option value="Metal">Metal</option>
                <option value="Organic">Organic / Food Waste</option>
                <option value="Electronic">Electronic Waste</option>
                <option value="Other">Other</option>
            </select>

            <label>Location / Address *</label>
            <input type="text" name="location" placeholder="Enter full address where waste is present" required>

            <label>Description</label>
            <textarea name="description" placeholder="Describe the waste (optional)"></textarea>

            <label>Your Name *</label>
            <input type="text" name="contact_name" placeholder="Enter your name" required>

            <label>Contact Phone *</label>
            <input type="text" name="contact_phone" placeholder="Enter your phone number" required>

            <button type="submit">Submit Request</button>
        </form>
    </div>

    <div class="card">
        <h2>All Waste Collection Requests</h2>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Waste Type</th>
                    <th>Location</th>
                    <th>Contact</th>
                    <th>Phone</th>
                    <th>Status</th>
                    <th>Submitted</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($requests) == 0): ?>
                    <tr><td colspan="7" style="text-align:center;padding:20px;color:#888;">No requests submitted yet</td></tr>
                <?php else: ?>
                    <?php while ($row = mysqli_fetch_assoc($requests)): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['waste_type']; ?></td>
                            <td><?php echo $row['location']; ?></td>
                            <td><?php echo $row['contact_name']; ?></td>
                            <td><?php echo $row['contact_phone']; ?></td>
                            <td><span class="badge <?php echo $row['status']; ?>"><?php echo ucfirst($row['status']); ?></span></td>
                            <td><?php echo date('d M Y', strtotime($row['submitted_at'])); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
