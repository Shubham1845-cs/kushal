<?php
require 'db.php';

$success = '';
$error = '';

// Handle booking
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['book'])) {
    $seat_id   = intval($_POST['seat_id']);
    $pname     = htmlspecialchars(trim($_POST['passenger_name']));
    $pemail    = htmlspecialchars(trim($_POST['passenger_email']));

    if (empty($pname) || empty($pemail)) {
        $error = 'Please enter passenger name and email.';
    } else {
        // Check seat is still available
        $check = mysqli_prepare($conn, "SELECT is_booked, seat_number FROM seats WHERE id = ?");
        mysqli_stmt_bind_param($check, 'i', $seat_id);
        mysqli_stmt_execute($check);
        $res = mysqli_stmt_get_result($check);
        $seat = mysqli_fetch_assoc($res);
        mysqli_stmt_close($check);

        if ($seat && !$seat['is_booked']) {
            $stmt = mysqli_prepare($conn, "UPDATE seats SET is_booked=1, passenger_name=?, passenger_email=?, booked_at=NOW() WHERE id=?");
            mysqli_stmt_bind_param($stmt, 'ssi', $pname, $pemail, $seat_id);
            if (mysqli_stmt_execute($stmt)) {
                $success = "Seat <strong>" . $seat['seat_number'] . "</strong> booked successfully for <strong>$pname</strong>!";
            }
            mysqli_stmt_close($stmt);
        } else {
            $error = 'This seat is already booked. Please select another.';
        }
    }
}

// Handle cancel
if (isset($_GET['cancel'])) {
    $seat_id = intval($_GET['cancel']);
    mysqli_query($conn, "UPDATE seats SET is_booked=0, passenger_name=NULL, passenger_email=NULL, booked_at=NULL WHERE id=$seat_id");
    header("Location: index.php");
    exit();
}

// Fetch all seats
$result = mysqli_query($conn, "SELECT * FROM seats ORDER BY id");
$seats = [];
while ($row = mysqli_fetch_assoc($result)) {
    $seats[] = $row;
}

// Group by row
$rows = [];
foreach ($seats as $seat) {
    preg_match('/^(\d+)([A-F])$/', $seat['seat_number'], $m);
    $rows[$m[1]][] = $seat;
}

$total   = count($seats);
$booked  = count(array_filter($seats, fn($s) => $s['is_booked']));
$available = $total - $booked;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Airplane Seat Booking</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f0f0f0; padding: 20px; }
        .container { max-width: 950px; margin: 0 auto; }
        h1 { text-align: center; color: #333; margin-bottom: 20px; }
        .card { background: white; padding: 25px; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1); margin-bottom: 25px; }
        h2 { color: #333; margin-bottom: 15px; border-bottom: 2px solid #1565c0; padding-bottom: 8px; }
        .stats { display: flex; gap: 15px; margin-bottom: 20px; }
        .stat { flex: 1; padding: 12px; border-radius: 3px; text-align: center; color: white; font-weight: bold; }
        .stat-total    { background: #1565c0; }
        .stat-booked   { background: #c62828; }
        .stat-available{ background: #2e7d32; }
        .legend { display: flex; gap: 20px; margin-bottom: 15px; font-size: 13px; }
        .legend span { display: inline-block; width: 20px; height: 20px; border-radius: 3px; margin-right: 5px; vertical-align: middle; }
        .leg-available { background: #a5d6a7; border: 1px solid #388e3c; }
        .leg-booked    { background: #ef9a9a; border: 1px solid #c62828; }
        .leg-business  { background: #90caf9; border: 1px solid #1565c0; }

        /* Airplane layout */
        .plane { background: #f9f9f9; border: 2px solid #ccc; border-radius: 10px; padding: 20px; }
        .plane-nose { text-align: center; font-size: 30px; margin-bottom: 10px; }
        .seat-row { display: flex; align-items: center; margin-bottom: 6px; }
        .row-label { width: 30px; font-weight: bold; color: #555; font-size: 13px; }
        .aisle { width: 20px; }
        .seat {
            width: 38px; height: 38px; margin: 2px;
            border-radius: 4px; border: 1px solid #999;
            display: flex; align-items: center; justify-content: center;
            font-size: 11px; font-weight: bold; cursor: pointer;
        }
        .seat.available { background: #a5d6a7; border-color: #388e3c; color: #1b5e20; }
        .seat.available:hover { background: #81c784; }
        .seat.booked    { background: #ef9a9a; border-color: #c62828; color: #7f0000; cursor: not-allowed; }
        .seat.business  { background: #90caf9; border-color: #1565c0; color: #0d47a1; }
        .seat.business.booked { background: #ef9a9a; }
        .col-labels { display: flex; margin-bottom: 4px; margin-left: 30px; }
        .col-label { width: 42px; text-align: center; font-weight: bold; font-size: 12px; color: #555; }
        /* Booking form */
        label { display: block; font-weight: bold; color: #333; margin-bottom: 4px; margin-top: 10px; }
        input { width: 100%; padding: 9px; border: 1px solid #ddd; border-radius: 3px; font-size: 14px; }
        button { margin-top: 12px; padding: 10px 20px; background: #1565c0; color: white; border: none; border-radius: 3px; cursor: pointer; font-weight: bold; }
        button:hover { background: #0d47a1; }
        .success { background: #e8f5e9; color: #2e7d32; padding: 12px; border-radius: 3px; margin-bottom: 15px; border-left: 4px solid #4caf50; }
        .error   { background: #ffebee; color: #c62828; padding: 12px; border-radius: 3px; margin-bottom: 15px; border-left: 4px solid #d32f2f; }
        .selected-info { background: #e3f2fd; padding: 10px; border-radius: 3px; margin-bottom: 10px; border-left: 4px solid #1565c0; }

        /* Booked list */
        table { width: 100%; border-collapse: collapse; font-size: 13px; }
        th, td { padding: 10px 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #1565c0; color: white; }
        tr:hover { background: #f5f5f5; }
        .cancel-btn { padding: 4px 10px; background: #c62828; color: white; border: none; border-radius: 3px; cursor: pointer; font-size: 12px; }
        .cancel-btn:hover { background: #b71c1c; }
    </style>
</head>
<body>
<div class="container">
    <h1>✈️ Airplane Seat Booking</h1>

    <?php if ($success): ?>
        <div class="success" style="margin-bottom:15px;"><?php echo $success; ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="error" style="margin-bottom:15px;"><?php echo $error; ?></div>
    <?php endif; ?>

    <div class="card">
        <h2>Seat Availability</h2>

        <div class="stats">
            <div class="stat stat-total">Total Seats: <?php echo $total; ?></div>
            <div class="stat stat-booked">Booked: <?php echo $booked; ?></div>
            <div class="stat stat-available">Available: <?php echo $available; ?></div>
        </div>

        <div class="legend">
            <div><span class="leg-business"></span> Business Class (Available)</div>
            <div><span class="leg-available"></span> Economy (Available)</div>
            <div><span class="leg-booked"></span> Booked</div>
        </div>

        <div class="plane">
            <div class="plane-nose">✈</div>
            <div class="col-labels">
                <div class="col-label">A</div>
                <div class="col-label">B</div>
                <div class="col-label">C</div>
            </div>

            <?php foreach ($rows as $rowNum => $rowSeats): ?>
                <div class="seat-row">
                    <div class="row-label"><?php echo $rowNum; ?></div>
                    <?php
                    $isBusiness = ($rowSeats[0]['class'] == 'Business');
                    ?>
                    <?php foreach ($rowSeats as $seat): ?>
                        <?php
                        $cls = $seat['is_booked'] ? 'booked' : ($isBusiness ? 'business' : 'available');
                        $title = $seat['is_booked'] ? 'Booked: ' . $seat['passenger_name'] : 'Click to book seat ' . $seat['seat_number'];
                        ?>
                        <div class="seat <?php echo $cls; ?>"
                             title="<?php echo $title; ?>"
                             <?php if (!$seat['is_booked']): ?>onclick="selectSeat(<?php echo $seat['id']; ?>, '<?php echo $seat['seat_number']; ?>', '<?php echo $seat['class']; ?>')"<?php endif; ?>>
                            <?php echo $seat['seat_number']; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Booking Form -->
    <div class="card" id="bookingForm">
        <h2>Book a Seat</h2>
        <div class="selected-info" id="selectedInfo">No seat selected. Click on an available seat above.</div>
        <form method="POST">
            <input type="hidden" name="seat_id" id="seat_id" value="">
            <input type="hidden" name="book" value="1">
            <label>Passenger Name *</label>
            <input type="text" name="passenger_name" placeholder="Enter passenger full name" required>
            <label>Passenger Email *</label>
            <input type="email" name="passenger_email" placeholder="Enter passenger email" required>
            <button type="submit">Confirm Booking</button>
        </form>
    </div>

    <!-- Booked Seats List -->
    <div class="card">
        <h2>Booked Seats</h2>
        <table>
            <thead>
                <tr><th>Seat</th><th>Class</th><th>Passenger</th><th>Email</th><th>Booked At</th><th>Action</th></tr>
            </thead>
            <tbody>
                <?php
                $bookedSeats = array_filter($seats, fn($s) => $s['is_booked']);
                if (empty($bookedSeats)):
                ?>
                    <tr><td colspan="6" style="text-align:center;padding:20px;color:#888;">No seats booked yet</td></tr>
                <?php else: ?>
                    <?php foreach ($bookedSeats as $s): ?>
                        <tr>
                            <td><strong><?php echo $s['seat_number']; ?></strong></td>
                            <td><?php echo $s['class']; ?></td>
                            <td><?php echo $s['passenger_name']; ?></td>
                            <td><?php echo $s['passenger_email']; ?></td>
                            <td><?php echo date('d M Y H:i', strtotime($s['booked_at'])); ?></td>
                            <td><a href="?cancel=<?php echo $s['id']; ?>" onclick="return confirm('Cancel this booking?')"><button class="cancel-btn">Cancel</button></a></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function selectSeat(id, seatNum, seatClass) {
    document.getElementById('seat_id').value = id;
    document.getElementById('selectedInfo').innerHTML =
        'Selected Seat: <strong>' + seatNum + '</strong> | Class: <strong>' + seatClass + '</strong>';
    document.getElementById('bookingForm').scrollIntoView({ behavior: 'smooth' });
    document.getElementById('passenger_name').focus();
}
</script>
</body>
</html>
