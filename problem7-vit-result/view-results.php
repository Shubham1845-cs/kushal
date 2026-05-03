<?php
$connection = new mysqli('localhost', 'root', '');
if ($connection->connect_error) {
    die('<div style="text-align:center; padding:20px; color:red;">Database connection failed: ' . $connection->connect_error . '</div>');
}

$connection->select_db('vit_results');
$result = $connection->query('SELECT * FROM semester_results ORDER BY created_at DESC');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saved Results - MySQL Database</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, sans-serif; background: #f5f5f5; padding: 20px; }
        .container { max-width: 1200px; margin: 0 auto; }
        h1 { text-align: center; color: #333; margin-bottom: 30px; }
        .back-link { display: inline-block; margin-bottom: 20px; padding: 10px 15px; background: #2563eb; color: white; text-decoration: none; border-radius: 4px; }
        .back-link:hover { background: #1d4ed8; }
        table { width: 100%; border-collapse: collapse; background: white; border-radius: 6px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        th { background: #f5f5f5; padding: 12px; text-align: left; font-weight: 600; color: #333; border-bottom: 1px solid #ddd; font-size: 13px; }
        td { padding: 12px; border-bottom: 1px solid #f0f0f0; font-size: 13px; color: #555; }
        tr:hover { background: #fafafa; }
        .grade { font-weight: 600; padding: 4px 8px; border-radius: 3px; text-align: center; }
        .grade-a { background: #d1fae5; color: #065f46; }
        .grade-b { background: #bfdbfe; color: #1e40af; }
        .grade-c { background: #fef3c7; color: #92400e; }
        .grade-d { background: #fee2e2; color: #7f1d1d; }
        .no-data { text-align: center; padding: 40px; color: #999; }
        .timestamp { color: #999; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <a href="index.html" class="back-link">← Back to Problem 7</a>
        <h1>Saved Semester Results (MySQL Database)</h1>
        
        <?php if ($result && $result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Student Name</th>
                        <th>Course</th>
                        <th>Subject 1 (Marks)</th>
                        <th>Subject 2 (Marks)</th>
                        <th>Subject 3 (Marks)</th>
                        <th>Subject 4 (Marks)</th>
                        <th>Grand Total</th>
                        <th>Percentage</th>
                        <th>Overall Grade</th>
                        <th>Saved At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['student_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['course']); ?></td>
                            <td><?php echo $row['subject_1_name'] . ' (' . $row['subject_1_mse'] . ' + ' . $row['subject_1_ese'] . ')'; ?></td>
                            <td><?php echo $row['subject_2_name'] . ' (' . $row['subject_2_mse'] . ' + ' . $row['subject_2_ese'] . ')'; ?></td>
                            <td><?php echo $row['subject_3_name'] . ' (' . $row['subject_3_mse'] . ' + ' . $row['subject_3_ese'] . ')'; ?></td>
                            <td><?php echo $row['subject_4_name'] . ' (' . $row['subject_4_mse'] . ' + ' . $row['subject_4_ese'] . ')'; ?></td>
                            <td><strong><?php echo $row['grand_total']; ?> / 400</strong></td>
                            <td><strong><?php echo number_format($row['grand_percentage'], 2); ?>%</strong></td>
                            <td>
                                <span class="grade grade-<?php echo strtolower($row['overall_grade'][0]); ?>">
                                    <?php echo $row['overall_grade']; ?>
                                </span>
                            </td>
                            <td><span class="timestamp"><?php echo date('M d, Y h:i A', strtotime($row['created_at'])); ?></span></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="no-data">
                <p>No results saved yet. Go to <a href="index.html" style="color: #2563eb; text-decoration: underline;">Problem 7</a> and click "Save Result to MySQL" to see results here.</p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
<?php $connection->close(); ?>
