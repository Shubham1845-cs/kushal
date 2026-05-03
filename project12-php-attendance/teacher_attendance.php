<?php
require 'db.php';

$date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');
$error = '';
$success = '';

// Handle attendance submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $date_submit = htmlspecialchars($_POST['date']);
    
    foreach ($_POST['attendance'] ?? [] as $student_id => $present) {
        $present = intval($present);
        $query = "INSERT INTO attendance (student_id, date, present) VALUES (?, ?, ?) 
                  ON DUPLICATE KEY UPDATE present = ?";
        $stmt = $connection->prepare($query);
        $stmt->bind_param('isii', $student_id, $date_submit, $present, $present);
        $stmt->execute();
        $stmt->close();
    }
    $success = "Attendance marked for " . $date_submit;
}

// Fetch all students
$query = "SELECT * FROM students ORDER BY roll_no";
$result = $connection->query($query);
$students = $result->fetch_all(MYSQLI_ASSOC);

// Fetch attendance for selected date
$attendance = [];
if (!empty($students)) {
    $query = "SELECT * FROM attendance WHERE date = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param('s', $date);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $attendance[$row['student_id']] = $row['present'];
    }
    $stmt->close();
}

// Get statistics
$total = count($students);
$present = count(array_filter($attendance, function($p) { return $p == 1; }));
$absent = $total - $present;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Teacher Attendance - Attendance System</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; padding: 20px; }
        .container { max-width: 900px; margin: 0 auto; background: white; padding: 25px; border-radius: 5px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #333; margin-bottom: 20px; border-bottom: 2px solid #ff9800; padding-bottom: 10px; }
        .date-select { margin-bottom: 20px; }
        label { font-weight: bold; color: #333; margin-right: 10px; }
        input[type="date"] { padding: 8px; border: 1px solid #ddd; border-radius: 3px; }
        .stats { display: flex; gap: 15px; margin: 15px 0; }
        .stat-box { padding: 15px; border-radius: 3px; flex: 1; text-align: center; color: white; font-weight: bold; }
        .stat-total { background: #2196F3; }
        .stat-present { background: #4caf50; }
        .stat-absent { background: #f44336; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #ff9800; color: white; }
        tr:hover { background: #f5f5f5; }
        .checkbox-cell { text-align: center; }
        input[type="checkbox"] { width: 20px; height: 20px; cursor: pointer; }
        button { padding: 10px 20px; background: #ff9800; color: white; border: none; border-radius: 3px; cursor: pointer; font-weight: bold; margin-top: 10px; }
        button:hover { background: #f57c00; }
        .success { background: #e8f5e9; color: #2e7d32; padding: 12px; border-radius: 3px; margin-bottom: 15px; border-left: 4px solid #4caf50; }
        .error { background: #ffebee; color: #c62828; padding: 12px; border-radius: 3px; margin-bottom: 15px; border-left: 4px solid #d32f2f; }
    </style>
</head>
<body>
    <div class="container">
        <h1>👨‍🏫 Teacher - Mark Attendance</h1>

        <?php if ($success): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>

        <div class="date-select">
            <label>Select Date:</label>
            <input type="date" id="dateInput" value="<?php echo $date; ?>" onchange="changeDate()">
        </div>

        <div class="stats">
            <div class="stat-box stat-total">Total Students: <?php echo $total; ?></div>
            <div class="stat-box stat-present">Present: <?php echo $present; ?></div>
            <div class="stat-box stat-absent">Absent: <?php echo $absent; ?></div>
        </div>

        <form method="POST">
            <input type="hidden" name="date" value="<?php echo $date; ?>">
            
            <table>
                <thead>
                    <tr>
                        <th>Roll No</th>
                        <th>Student Name</th>
                        <th style="text-align: center;">Present</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $student): ?>
                        <tr>
                            <td><strong><?php echo $student['roll_no']; ?></strong></td>
                            <td><?php echo $student['name']; ?></td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="attendance[<?php echo $student['id']; ?>]" value="1" 
                                    <?php echo (isset($attendance[$student['id']]) && $attendance[$student['id']]) ? 'checked' : ''; ?>>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <button type="submit">Save Attendance</button>
        </form>
    </div>

    <script>
        function changeDate() {
            let date = document.getElementById('dateInput').value;
            window.location.href = '?date=' + date;
        }
    </script>
</body>
</html>
