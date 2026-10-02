<?php
session_start();
include("connect.php");

if (!isset($_SESSION['username'])) {
    header("Location: login.php"); 
    exit();
}

$username = $_SESSION['username'];
$transactions_data = [];

$query = "SELECT 
    t.transactionID, 
    t.totalPrice, 
    t.transactionDate, 
    u.username
FROM transactions t
JOIN users u ON t.userID = u.userID
ORDER BY u.username ASC";

$result = mysqli_query($conn, $query);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $transactions_data[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>All Transaction - Admin Furniland</title>
    <link rel="stylesheet" href="style.css" />
  </head>

  <body>
    <nav class="navbar">
      <div class="nav-kolom-left">
        <div class="nav-left">Furniland</div>
        <ul class="nav-menu">
          <li><a href="Home.php" class="biru">Dashboard</a></li>
        </ul>
      </div>
      <div class="nav-right">
        <p>
            <a href="Profile.php" class="Hello-User">
                Hello, <?php echo htmlspecialchars($username); ?>
            </a>
        </p>
        <a href="logout.php" class="merah">Log Out</a>
      </div>
    </nav>

    <header class="header-admin">
      <div>
        <h1>All Transaction</h1>
      </div>
    </header>

    <main class="admin-main">
      <div class="table-container">
        <table class="table">
          <thead>
            <tr>
              <th>Transaction ID</th>
              <th>User</th>
              <th>Total Price</th>
              <th>Date</th>
            </tr>
          </thead>

          <tbody>
            <?php 
            if (empty($transactions_data)) {
                echo '<tr><td colspan="4" style="text-align: center;">No transactions found.</td></tr>';
            } else {
                foreach ($transactions_data as $transaction) {
                    $transactionID_formatted = '#' . htmlspecialchars($transaction['transactionID']);
                    $totalPrice_formatted = 'Rp ' . number_format($transaction['totalPrice'], 0, ',', '.');
                    $date_formatted = date('d M Y', strtotime($transaction['transactionDate']));
                    
                    echo '<tr>';
                    echo '<td>' . $transactionID_formatted . '</td>';
                    echo '<td>' . htmlspecialchars($transaction['username']) . '</td>';
                    echo '<td>' . $totalPrice_formatted . '</td>';
                    echo '<td>' . $date_formatted . '</td>';
                    echo '</tr>';
                }
            }
            ?>
          </tbody>
        </table>
      </div>
    </main>
    <footer class="footer">
      <p>© 2025 Furniland. All rights reserved.</p>
      <p>
        Contact us at
        <a href="mailto:furniland.support@gmail.com"
          >furniland.support@gmail.com</a
        >
      </p>
    </footer>

    <script src="script.js"></script>
  </body>
</html>