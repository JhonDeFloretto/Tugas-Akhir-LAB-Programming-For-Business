<?php
session_start();
include("connect.php");

if (!isset($_SESSION['username'])) {
    header("Location: Login.php");
    exit();
}

$username = $_SESSION['username'];
$transactions = []; 

$user_query = "SELECT userID FROM users WHERE username = '$username'";
$user_result = mysqli_query($conn, $user_query);
$userID = 0;
if ($user_result && $user_row = mysqli_fetch_assoc($user_result)) {
    $userID = $user_row['userID'];
}

$history_query = "SELECT t.transactionID, t.transactionDate, t.totalPrice, td.quantity, td.subtotal, p.productName
                  FROM transactions t
                  JOIN transaction_details td ON t.transactionID = td.transactionID
                  JOIN products p ON td.productID = p.productID
                  WHERE t.userID = '$userID'
                  ORDER BY t.transactionDate DESC";

$history_result = mysqli_query($conn, $history_query);

if ($history_result && mysqli_num_rows($history_result) > 0) {
    while ($row = mysqli_fetch_assoc($history_result)) {
        $id = $row['transactionID'];

        if (!isset($transactions[$id])) {
            $transactions[$id] = [
                'id' => $id,
                'date' => date('d M Y', strtotime($row['transactionDate'])),
                'total' => $row['totalPrice'], 
                'items' => []
            ];
        }

        $transactions[$id]['items'][] = [
            'name' => $row['productName'],
            'quantity' => $row['quantity'],
            'subtotal' => $row['subtotal']
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Transaction History Furniland</title>
    <link rel="stylesheet" href="style.css" />
  </head>

  <body>
    <nav class="navbar">
      <div class="nav-kolom-left">
        <div class="nav-left">Furniland</div>
        <ul class="nav-menu">
          <li><a href="Home.php" class="biru">Home</a></li>
          <li><a href="ProductCatalog.php" class="biru">Catalog</a></li>
        </ul>
      </div>
      <div class="nav-right">
        <p>
            <a href="Profile.php" class="Hello-User">
                Hello, <?php echo htmlspecialchars($username); ?>
            </a>
        </p>
        <a href="Cart.php" class="biru">Cart</a>
        <a href="History.php" class="biru">History</a> <a href="logout.php" class="merah">Log Out</a> </div>
    </nav>

    <header class="header">
      <h1>Transaction History</h1>
      <?php 
      if (isset($_GET['success']) && $_GET['success'] == 1) {
          echo "<p style='color: green;'>Checkout complete! See your transaction below.</p>";
      }
      ?>
    </header>

    <main class="history-container">
        <?php 
        if (empty($transactions)) { ?>
            <i id="If Your Cart is Empty" style="text-align: center; display: block; margin-top: 20px;">you haven't made any transaction yet</i>
        <?php } else {
            foreach ($transactions as $transaction) { 
            ?>
                <section class="transaction-card">
                  <div class="transaction-header">
                    <p class="transaction-id">Transaction ID: <?php echo htmlspecialchars($transaction['id']); ?></p>
                    <p class="transaction-total">Total: Rp <?php echo number_format($transaction['total'], 0, ',', '.'); ?></p>
                  </div>

                  <p class="transaction-date">Date: <?php echo htmlspecialchars($transaction['date']); ?></p>

                  <table class="transaction-table">
                    <thead>
                      <tr>
                        <th>Product</th>
                        <th>Qty</th>
                        <th>Subtotal</th>
                      </tr>
                    </thead>

                    <tbody>
                        <?php 
                        foreach ($transaction['items'] as $item) { 
                        ?>
                            <tr>
                              <td><?php echo htmlspecialchars($item['name']); ?></td>
                              <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                              <td>Rp <?php echo number_format($item['subtotal'], 0, ',', '.'); ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                  </table>
                </section>
            <?php 
            } 
        }
        ?>
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

  </body>
</html>