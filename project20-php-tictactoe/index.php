<?php
session_start();

// Initialize scores once
if (!isset($_SESSION['scores'])) {
    $_SESSION['scores'] = ['X' => 0, 'O' => 0, 'Draw' => 0];
}

// Clear scores
if (isset($_GET['clearscore'])) {
    $_SESSION['scores'] = ['X' => 0, 'O' => 0, 'Draw' => 0];
}

// Initialize / reset game
if (!isset($_SESSION['board']) || isset($_GET['reset'])) {
    $_SESSION['board']   = array_fill(0, 9, '');
    $_SESSION['current'] = 'X';
    $_SESSION['winner']  = null;
    $_SESSION['draw']    = false;
}

// Handle move
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cell']) && !$_SESSION['winner'] && !$_SESSION['draw']) {
    $cell = intval($_POST['cell']);
    if ($cell >= 0 && $cell <= 8 && $_SESSION['board'][$cell] === '') {
        $_SESSION['board'][$cell] = $_SESSION['current'];

        $wins = [[0,1,2],[3,4,5],[6,7,8],[0,3,6],[1,4,7],[2,5,8],[0,4,8],[2,4,6]];
        foreach ($wins as $combo) {
            if ($_SESSION['board'][$combo[0]] !== '' &&
                $_SESSION['board'][$combo[0]] === $_SESSION['board'][$combo[1]] &&
                $_SESSION['board'][$combo[1]] === $_SESSION['board'][$combo[2]]) {
                $_SESSION['winner'] = $_SESSION['current'];
                $_SESSION['scores'][$_SESSION['current']]++;
                break;
            }
        }

        if (!$_SESSION['winner'] && !in_array('', $_SESSION['board'])) {
            $_SESSION['draw'] = true;
            $_SESSION['scores']['Draw']++;
        }

        if (!$_SESSION['winner'] && !$_SESSION['draw']) {
            $_SESSION['current'] = ($_SESSION['current'] === 'X') ? 'O' : 'X';
        }
    }
}

$board   = $_SESSION['board'];
$current = $_SESSION['current'];
$winner  = $_SESSION['winner'];
$draw    = $_SESSION['draw'];
$scores  = $_SESSION['scores'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tic-Tac-Toe</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f0f0f0; padding: 20px; }
        .container { max-width: 400px; margin: 40px auto; background: white; padding: 25px; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1); text-align: center; }
        h1 { color: #333; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .scores { display: flex; justify-content: space-around; margin-bottom: 20px; }
        .score-box { padding: 10px 15px; border-radius: 3px; font-weight: bold; }
        .score-x    { background: #e3f2fd; color: #1565c0; border: 1px solid #1565c0; }
        .score-o    { background: #fce4ec; color: #c62828; border: 1px solid #c62828; }
        .score-draw { background: #f5f5f5; color: #555; border: 1px solid #999; }
        .status { font-size: 18px; font-weight: bold; margin-bottom: 15px; padding: 10px; border-radius: 3px; }
        .status.playing { background: #fff9c4; color: #f57f17; }
        .status.winner  { background: #e8f5e9; color: #2e7d32; }
        .status.draw    { background: #f5f5f5; color: #555; }
        .board { display: grid; grid-template-columns: repeat(3, 100px); gap: 5px; margin-bottom: 20px; justify-content: center; }
        .cell-form { margin: 0; }
        .cell {
            width: 100px; height: 100px;
            font-size: 40px; font-weight: bold;
            border: 2px solid #333; border-radius: 3px;
            background: #fafafa; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            font-family: Arial, sans-serif;
        }
        .cell:hover { background: #e3f2fd; }
        .cell.taken { cursor: not-allowed; background: #fafafa; }
        .cell.x { color: #1565c0; }
        .cell.o { color: #c62828; }
        .btn { padding: 10px 25px; background: #333; color: white; border: none; border-radius: 3px; cursor: pointer; font-size: 15px; font-weight: bold; }
        .btn:hover { background: #555; }
    </style>
</head>
<body>
<div class="container">
    <h1>Tic-Tac-Toe</h1>

    <!-- Scores -->
    <div class="scores">
        <div class="score-box score-x">X Wins: <?php echo $scores['X']; ?></div>
        <div class="score-box score-draw">Draws: <?php echo $scores['Draw']; ?></div>
        <div class="score-box score-o">O Wins: <?php echo $scores['O']; ?></div>
    </div>

    <!-- Status -->
    <?php if ($winner): ?>
        <div class="status winner">Player <?php echo $winner; ?> Wins!</div>
    <?php elseif ($draw): ?>
        <div class="status draw">It's a Draw!</div>
    <?php else: ?>
        <div class="status playing">Player <?php echo $current; ?>'s Turn</div>
    <?php endif; ?>

    <!-- Board -->
    <div class="board">
        <?php for ($i = 0; $i < 9; $i++): ?>
            <?php $val = $board[$i]; ?>
            <?php if ($val !== '' || $winner || $draw): ?>
                <div class="cell taken <?php echo strtolower($val); ?>">
                    <?php echo $val; ?>
                </div>
            <?php else: ?>
                <form method="POST" class="cell-form">
                    <input type="hidden" name="cell" value="<?php echo $i; ?>">
                    <button type="submit" class="cell" style="width:100px;height:100px;border:2px solid #333;">
                    </button>
                </form>
            <?php endif; ?>
        <?php endfor; ?>
    </div>

    <!-- Reset -->
    <a href="?reset=1"><button class="btn">New Game</button></a>
    &nbsp;
    <a href="?reset=1&clearscore=1"><button class="btn" style="background:#888;">Reset Scores</button></a>
</div>
</body>
</html>
