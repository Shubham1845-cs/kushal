<?php
require 'db.php';
$success = '';
$error = '';

// DELETE
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = mysqli_prepare($conn, "DELETE FROM students WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    if (mysqli_stmt_execute($stmt)) {
        $success = 'Student deleted successfully.';
    } else {
        $error = 'Failed to delete student.';
    }
    mysqli_stmt_close($stmt);
}

// ADD / EDIT SUBMIT
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id     = intval($_POST['id'] ?? 0);
    $name   = htmlspecialchars(trim($_POST['name']));
    $email  = htmlspecialchars(trim($_POST['email']));
    $course = htmlspecialchars(trim($_POST['course']));
    $phone  = htmlspecialchars(trim($_POST['phone']));

    if (empty($name) || empty($email) || empty($course)) {
        $error = 'Name, Email and Course are required.';
    } else {
        if ($id > 0) {
            // UPDATE
            $stmt = mysqli_prepare($conn, "UPDATE students SET name=?, email=?, course=?, phone=? WHERE id=?");
            mysqli_stmt_bind_param($stmt, 'ssssi', $name, $email, $course, $phone, $id);
            $action = 'updated';
        } else {
            // INSERT
            $stmt = mysqli_prepare($conn, "INSERT INTO students (name, email, course, phone) VALUES (?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt, 'ssss', $name, $email, $course, $phone);
            $action = 'added';
        }
        if (mysqli_stmt_execute($stmt)) {
            $success = "Student $action successfully.";
        } else {
            $error = 'Email already exists or operation failed.';
        }
        mysqli_stmt_close($stmt);
    }
}

// FETCH EDIT DATA
$editStudent = null;
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $res = mysqli_query($conn, "SELECT * FROM students WHERE id = $id");
    $editStudent = mysqli_fetch_assoc($res);
}

// FETCH ALL
$students = mysqli_query($conn, "SELECT * FROM students ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Records - CRUD</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f0f0f0; padding: 20px; }
        .container { max-width: 960px; margin: 0 auto; }
        h1 { text-align: center; color: #333; margin-bottom: 20px; }
        .card { background: white; padding: 25px; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1); margin-bottom: 25px; }
        h2 { color: #333; margin-bottom: 15px; border-bottom: 2px solid #0066cc; padding-bottom: 8px; }
        label { display: block; font-weight: bold; margin-bottom: 4px; margin-top: 10px; }
        input { width: 100%; padding: 9px; border: 1px solid #ddd; border-radius: 3px; font-size: 14px; }
        .row { display: flex; gap: 15px; flex-wrap: wrap; }
        .row > div { flex: 1; min-width: 200px; }
        .btn { padding: 10px 20px; border: none; border-radius: 3px; cursor: pointer; font-weight: bold; font-size: 14px; margin-top: 15px; }
        .btn-primary { background: #0066cc; color: white; }
        .btn-primary:hover { background: #0052a3; }
        .btn-secondary { background: #888; color: white; margin-left: 10px; }
        .btn-secondary:hover { background: #666; }
        .success { background: #e8f5e9; color: #2e7d32; padding: 12px; border-radius: 3px; margin-bottom: 15px; border-left: 4px solid #4caf50; }
        .error   { background: #ffebee; color: #c62828; padding: 12px; border-radius: 3px; margin-bottom: 15px; border-left: 4px solid #d32f2f; }
        table { width: 100%; border-collapse: collapse; font-size: 14px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #0066cc; color: white; }
        tr:hover { background: #f5f5f5; }
        .btn-edit   { padding: 5px 12px; background: #ff9800; color: white; border: none; border-radius: 3px; cursor: pointer; font-size: 13px; }
        .btn-delete { padding: 5px 12px; background: #d32f2f; color: white; border: none; border-radius: 3px; cursor: pointer; font-size: 13px; margin-left: 5px; }
        .btn-edit:hover   { background: #f57c00; }
        .btn-delete:hover { background: #b71c1c; }
        .edit-mode { border-left: 4px solid #ff9800; }

        /* Responsive */
        @media (max-width: 600px) {
            .row > div { min-width: 100%; }
            table { font-size: 12px; }
            th, td { padding: 8px 6px; }
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Student Records Management</h1>

    <?php if ($success): ?><div class="success"><?php echo $success; ?></div><?php endif; ?>
    <?php if ($error):   ?><div class="error"><?php echo $error; ?></div><?php endif; ?>

    <!-- Add / Edit Form -->
    <div class="card <?php echo $editStudent ? 'edit-mode' : ''; ?>">
        <h2><?php echo $editStudent ? 'Edit Student' : 'Add New Student'; ?></h2>
        <form method="POST">
            <input type="hidden" name="id" value="<?php echo $editStudent ? $editStudent['id'] : 0; ?>">
            <div class="row">
                <div>
                    <label>Full Name *</label>
                    <input type="text" name="name" value="<?php echo $editStudent ? $editStudent['name'] : ''; ?>" placeholder="Enter full name" required>
                </div>
                <div>
                    <label>Email *</label>
                    <input type="email" name="email" value="<?php echo $editStudent ? $editStudent['email'] : ''; ?>" placeholder="Enter email" required>
                </div>
            </div>
            <div class="row">
                <div>
                    <label>Course *</label>
                    <input type="text" name="course" value="<?php echo $editStudent ? $editStudent['course'] : ''; ?>" placeholder="Enter course name" required>
                </div>
                <div>
                    <label>Phone</label>
                    <input type="text" name="phone" value="<?php echo $editStudent ? $editStudent['phone'] : ''; ?>" placeholder="Enter phone number">
                </div>
            </div>
            <button type="submit" class="btn btn-primary"><?php echo $editStudent ? 'Update Student' : 'Add Student'; ?></button>
            <?php if ($editStudent): ?>
                <a href="index.php"><button type="button" class="btn btn-secondary">Cancel</button></a>
            <?php endif; ?>
        </form>
    </div>

    <!-- Student List -->
    <div class="card">
        <h2>All Students (<?php echo mysqli_num_rows($students); ?>)</h2>
        <div style="overflow-x:auto;">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Course</th>
                        <th>Phone</th>
                        <th>Added On</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($students) == 0): ?>
                        <tr><td colspan="7" style="text-align:center;padding:20px;color:#888;">No students found</td></tr>
                    <?php else: ?>
                        <?php while ($row = mysqli_fetch_assoc($students)): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['name']; ?></td>
                                <td><?php echo $row['email']; ?></td>
                                <td><?php echo $row['course']; ?></td>
                                <td><?php echo $row['phone'] ?: '-'; ?></td>
                                <td><?php echo date('d M Y', strtotime($row['created_at'])); ?></td>
                                <td>
                                    <a href="?edit=<?php echo $row['id']; ?>">
                                        <button class="btn-edit">Edit</button>
                                    </a>
                                    <a href="?delete=<?php echo $row['id']; ?>" onclick="return confirm('Delete this student?')">
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
