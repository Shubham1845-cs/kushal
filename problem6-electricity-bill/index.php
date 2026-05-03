<?php
$totalBill = '';
$billDetails = '';
$units = '';

if (isset($_POST['calculate'])) {
    $units = (float) ($_POST['units'] ?? 0);
    
    if ($units < 0) {
        $totalBill = 'Units cannot be negative.';
    } else {
        $bill = 0;
        $details = [];
        
        if ($units <= 50) {
            $bill = $units * 3.50;
            $details[] = "First $units units @ Rs. 3.50/unit = Rs. " . ($units * 3.50);
        } elseif ($units <= 150) {
            $bill = (50 * 3.50) + (($units - 50) * 4.00);
            $details[] = "First 50 units @ Rs. 3.50/unit = Rs. " . (50 * 3.50);
            $details[] = "Next " . ($units - 50) . " units @ Rs. 4.00/unit = Rs. " . (($units - 50) * 4.00);
        } elseif ($units <= 250) {
            $bill = (50 * 3.50) + (100 * 4.00) + (($units - 150) * 5.20);
            $details[] = "First 50 units @ Rs. 3.50/unit = Rs. " . (50 * 3.50);
            $details[] = "Next 100 units @ Rs. 4.00/unit = Rs. " . (100 * 4.00);
            $details[] = "Next " . ($units - 150) . " units @ Rs. 5.20/unit = Rs. " . (($units - 150) * 5.20);
        } else {
            $bill = (50 * 3.50) + (100 * 4.00) + (100 * 5.20) + (($units - 250) * 6.50);
            $details[] = "First 50 units @ Rs. 3.50/unit = Rs. " . (50 * 3.50);
            $details[] = "Next 100 units @ Rs. 4.00/unit = Rs. " . (100 * 4.00);
            $details[] = "Next 100 units @ Rs. 5.20/unit = Rs. " . (100 * 5.20);
            $details[] = "Remaining " . ($units - 250) . " units @ Rs. 6.50/unit = Rs. " . (($units - 250) * 6.50);
        }
        
        $billDetails = '<div class="bill-details">';
        foreach ($details as $detail) {
            $billDetails .= '<p>' . htmlspecialchars($detail) . '</p>';
        }
        $billDetails .= '</div>';
        $totalBill = 'Total Bill: Rs. ' . number_format($bill, 2);
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Problem 6 - Electricity Bill Calculator</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>
  <div class="container">
    <div class="calculator-card">
      <h1>Electricity Bill Calculator</h1>
      <p class="subtitle">Calculate your monthly electricity bill based on unit consumption.</p>
      
      <form method="post" class="bill-form">
        <label for="units">Enter Units Consumed:</label>
        <input type="number" id="units" name="units" step="0.1" min="0" placeholder="e.g., 120" required />
        <button type="submit" name="calculate">Calculate Bill</button>
      </form>

      <div class="rates-info">
        <h3>Pricing Structure:</h3>
        <ul>
          <li>0-50 units: Rs. 3.50/unit</li>
          <li>51-150 units: Rs. 4.00/unit</li>
          <li>151-250 units: Rs. 5.20/unit</li>
          <li>250+ units: Rs. 6.50/unit</li>
        </ul>
      </div>

      <?php if ($totalBill): ?>
        <div class="result">
          <?php echo $billDetails; ?>
          <h2><?php echo htmlspecialchars($totalBill); ?></h2>
        </div>
      <?php endif; ?>
    </div>
  </div>
</body>
</html>
