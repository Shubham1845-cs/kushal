<?php
$connection = new mysqli('localhost', 'root', '');
if ($connection->connect_error) {
    die('Connection failed: ' . $connection->connect_error);
}

$connection->query('CREATE DATABASE IF NOT EXISTS student_db');
$connection->select_db('student_db');
$connection->query('CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
  prn VARCHAR(50) DEFAULT NULL,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL
)');

$message = '';
$editStudent = ['id' => '', 'prn' => '', 'name' => '', 'email' => ''];

if (isset($_GET['delete'])) {
    $deleteId = (int) $_GET['delete'];
    $stmt = $connection->prepare('DELETE FROM students WHERE id = ?');
    $stmt->bind_param('i', $deleteId);
    $stmt->execute();
    $stmt->close();
    $message = 'Record deleted successfully.';
}

if (isset($_GET['edit'])) {
    $editId = (int) $_GET['edit'];
  $stmt = $connection->prepare('SELECT id, prn, name, email FROM students WHERE id = ?');
    $stmt->bind_param('i', $editId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $editStudent = $row;
    }
    $stmt->close();
}

if (isset($_POST['save_student'])) {
    $studentId = (int) ($_POST['student_id'] ?? 0);
    $studentPrn = trim($_POST['student_prn'] ?? '');
    $studentName = trim($_POST['student_name'] ?? '');
    $studentEmail = trim($_POST['student_email'] ?? '');

  // Server-side validation: PRN required
  if ($studentPrn === '') {
    $message = 'PRN is required.';
  } else {
    // Check for duplicate PRN (different record)
    $check = $connection->prepare('SELECT id FROM students WHERE prn = ? LIMIT 1');
    $check->bind_param('s', $studentPrn);
    $check->execute();
    $res = $check->get_result();
    $existing = $res->fetch_assoc();
    $check->close();

    if ($existing && ($studentId === 0 || (int)$existing['id'] !== $studentId)) {
      $message = 'PRN already exists. Use a unique PRN.';
    } else {
      if ($studentId > 0) {
        $stmt = $connection->prepare('UPDATE students SET prn = ?, name = ?, email = ? WHERE id = ?');
        $stmt->bind_param('sssi', $studentPrn, $studentName, $studentEmail, $studentId);
        $stmt->execute();
        $stmt->close();
        $message = 'Record updated successfully.';
      } else {
        $stmt = $connection->prepare('INSERT INTO students (prn, name, email) VALUES (?, ?, ?)');
        $stmt->bind_param('sss', $studentPrn, $studentName, $studentEmail);
        $stmt->execute();
        $stmt->close();
        $message = 'Record inserted successfully.';
      }
    }
  }

    $editStudent = ['id' => '', 'name' => '', 'email' => ''];
}

$students = $connection->query('SELECT id, prn, name, email FROM students ORDER BY id DESC');
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Problem 5 - PHP MySQL</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>
  <div class="container">
    <h1>PHP and MySQL CRUD</h1>
    <p class="subtitle">Database: student_db | Table: students</p>

    <div class="card">
      <h2><?php echo ($editStudent['id'] ?? '') ? 'Update Student' : 'Insert Student'; ?></h2>
      <form method="post">
        <input type="hidden" name="student_id" value="<?php echo htmlspecialchars($editStudent['id'] ?? ''); ?>" />
        <input type="text" name="student_prn" placeholder="PRN" value="<?php echo htmlspecialchars($editStudent['prn'] ?? ''); ?>" required />
        <input type="text" name="student_name" placeholder="Name" value="<?php echo htmlspecialchars($editStudent['name'] ?? ''); ?>" required />
        <input type="email" name="student_email" placeholder="Email" value="<?php echo htmlspecialchars($editStudent['email'] ?? ''); ?>" required />
        <button type="submit" name="save_student">Save</button>
      </form>
      <div class="message"><?php echo $message; ?></div>
    </div>

    <div class="card">
      <h2>Student Records</h2>
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>PRN</th>
            <th>Name</th>
            <th>Email</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($students && $students->num_rows > 0): ?>
            <?php while ($row = $students->fetch_assoc()): ?>
              <tr>
                <td><?php echo htmlspecialchars($row['id']); ?></td>
                <td><?php echo htmlspecialchars($row['prn']); ?></td>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td class="actions">
                  <a href="?edit=<?php echo $row['id']; ?>">Edit</a>
                  <a href="?delete=<?php echo $row['id']; ?>" onclick="return confirm('Delete this record?')">Delete</a>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr><td colspan="4">No records found.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</body>
</html>
