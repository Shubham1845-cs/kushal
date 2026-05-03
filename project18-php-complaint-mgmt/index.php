<?php
require 'db.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name         = htmlspecialchars(trim($_POST['name']));
    $email        = htmlspecialchars(trim($_POST['email']));
    $phone        = htmlspecialchars(trim($_POST['phone']));
    $organization = htmlspecialchars(trim($_POST['organization']));
    $category     = htmlspecialchars(trim($_POST['category']));
    $subject      = htmlspecialchars(trim($_POST['subject']));
    $description  = htmlspecialchars(trim($_POST['description']));

    if (empty($name) || empty($email) || empty($organization) || empty($subject) || empty($description)) {
        $error = 'Please fill in all required fields.';
    } else {
        $stmt = mysqli_prepare($conn, "INSERT INTO complaints (name, email, phone, organization, category, subject, description) VALUES (?, ?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, 'sssssss', $name, $email, $phone, $organization, $category, $subject, $description);
        if (mysqli_stmt_execute($stmt)) {
            $id = mysqli_insert_id($conn);
            $success = "Complaint submitted successfully! Your complaint ID is: <strong>#$id</strong>. We will get back to you soon.";
        } else {
            $error = 'Failed to submit complaint. Please try again.';
        }
        mysqli_stmt_close($stmt);
    }
}

// Fetch all complaints
$complaints = mysqli_query($conn, "SELECT * FROM complaints ORDER BY submitted_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complaint Management System</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f0f0f0; padding: 20px; }
        .container { max-width: 950px; margin: 0 auto; }
        h1 { text-align: center; color: #333; margin-bottom: 20px; }
        .card { background: white; padding: 25px; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1); margin-bottom: 25px; }
        h2 { color: #333; margin-bottom: 15px; border-bottom: 2px solid #d32f2f; padding-bottom: 8px; }
        label { display: block; font-weight: bold; color: #333; margin-bottom: 4px; margin-top: 10px; }
        input, select, textarea { width: 100%; padding: 9px; border: 1px solid #ddd; border-radius: 3px; font-size: 14px; }
        textarea { height: 90px; resize: vertical; }
        .row { display: flex; gap: 15px; }
        .row > div { flex: 1; }
        button { margin-top: 15px; padding: 10px 20px; background: #d32f2f; color: white; border: none; border-radius: 3px; cursor: pointer; font-weight: bold; font-size: 14px; }
        button:hover { background: #b71c1c; }
        .success { background: #e8f5e9; color: #2e7d32; padding: 12px; border-radius: 3px; margin-bottom: 15px; border-left: 4px solid #4caf50; }
        .error   { background: #ffebee; color: #c62828; padding: 12px; border-radius: 3px; margin-bottom: 15px; border-left: 4px solid #d32f2f; }
        table { width: 100%; border-collapse: collapse; font-size: 13px; }
        th, td { padding: 10px 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #d32f2f; color: white; }
        tr:hover { background: #f9f9f9; }
        .badge { padding: 3px 8px; border-radius: 3px; font-size: 12px; font-weight: bold; }
        .open        { background: #fff3e0; color: #e65100; }
        .in-progress { background: #e3f2fd; color: #1565c0; }
        .resolved    { background: #e8f5e9; color: #2e7d32; }
        .closed      { background: #f5f5f5; color: #555; }
    </style>
</head>
<body>
<div class="container">
    <h1>📋 Complaint Management System</h1>

    <div class="card">
        <h2>Submit a Complaint</h2>

        <?php if ($success): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="row">
                <div>
                    <label>Your Name *</label>
                    <input type="text" name="name" placeholder="Enter your full name" required>
                </div>
                <div>
                    <label>Email *</label>
                    <input type="email" name="email" placeholder="Enter your email" required>
                </div>
            </div>

            <div class="row">
                <div>
                    <label>Phone</label>
                    <input type="text" name="phone" placeholder="Enter phone number (optional)">
                </div>
                <div>
                    <label>Organization *</label>
                    <select name="organization" required>
                        <option value="">-- Select Organization --</option>
                        <option value="PMC">PMC (Pune Municipal Corporation)</option>
                        <option value="PMT">PMT (Pune Mahanagar Transport)</option>
                        <option value="MSEB">MSEB (Electricity Board)</option>
                        <option value="Water Department">Water Department</option>
                        <option value="Road Department">Road Department</option>
                        <option value="Police">Police Department</option>
                        <option value="Other">Other Institution</option>
                    </select>
                </div>
            </div>

            <label>Category *</label>
            <select name="category" required>
                <option value="">-- Select Category --</option>
                <option value="Road / Pothole">Road / Pothole</option>
                <option value="Water Supply">Water Supply</option>
                <option value="Electricity">Electricity</option>
                <option value="Garbage Collection">Garbage Collection</option>
                <option value="Street Light">Street Light</option>
                <option value="Transport">Transport</option>
                <option value="Noise Pollution">Noise Pollution</option>
                <option value="Other">Other</option>
            </select>

            <label>Subject *</label>
            <input type="text" name="subject" placeholder="Brief subject of your complaint" required>

            <label>Description *</label>
            <textarea name="description" placeholder="Describe your complaint in detail..." required></textarea>

            <button type="submit">Submit Complaint</button>
        </form>
    </div>

    <div class="card">
        <h2>All Complaints</h2>
        <table>
            <thead>
                <tr>
                    <th>#ID</th>
                    <th>Name</th>
                    <th>Organization</th>
                    <th>Category</th>
                    <th>Subject</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($complaints) == 0): ?>
                    <tr><td colspan="7" style="text-align:center;padding:20px;color:#888;">No complaints submitted yet</td></tr>
                <?php else: ?>
                    <?php while ($row = mysqli_fetch_assoc($complaints)): ?>
                        <tr>
                            <td>#<?php echo $row['id']; ?></td>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['organization']; ?></td>
                            <td><?php echo $row['category']; ?></td>
                            <td><?php echo $row['subject']; ?></td>
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
