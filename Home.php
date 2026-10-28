<?php
session_start();
include("connect.php"); 

$role = 'guest';
$username = 'Guest';

if (isset($_SESSION['role']) && isset($_SESSION['username'])) {
    $role = $_SESSION['role'];
    $username = $_SESSION['username'];
}

$result = null;
if ($role !== 'admin') {
    $query = "SELECT * FROM products ORDER BY RAND() LIMIT 6";
    $result = $conn->query($query); 
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Home Furniland - <?php echo ucfirst($role); ?></title>
    <link rel="stylesheet" href="style.css" />
  </head>

  <body>
    <nav class="navbar">
      <div class="nav-kolom-left">
        <div class="nav-left">Furniland</div>
        <ul class="nav-menu">
        <?php if ($role == 'admin'): ?>
            <li><a href="Home.php" class="biru">Dashboard</a></li>
        <?php else: ?>
            <li><a href="Home.php" class="biru">Home</a></li>
            <?php if ($role == 'member'): ?>
                <li><a href="ProductCatalog.php" class="biru">Catalog</a></li>
            <?php endif; ?>
        <?php endif; ?>
        </ul>
      </div>
      <div class="nav-right">
        <?php if ($role == 'guest'): ?>
            <a href="Login.php" class="biru">Login</a>
        <?php else: ?>
            <p>
                <a href="Profile.php" class="Hello-User">
                    Hello, <?php echo htmlspecialchars($username); ?>
                </a>
            </p>
            <?php if ($role == 'member'): ?>
                <a href="Cart.php" class="biru">Cart</a>
                <a href="History.php" class="biru">History</a>
            <?php endif; ?>
            <a href="logout.php" class="merah">Log Out</a>
        <?php endif; ?>
      </div>
    </nav>

    <?php if ($role == 'admin'): ?>
        <header class="header-admin">
            <div>
                <h1>Admin Dashboard</h1>
            </div>
        </header>
    <?php else: ?>
        <header class="header">
            <h1>Furniland</h1>
            <p>Furnitures you might love</p>
        </header>
    <?php endif; ?>

    <main class="<?php echo ($role == 'admin') ? 'admin-utama' : ''; ?>">
    
    <?php if ($role == 'admin'): ?>
        <section class="Data-Managing">
            <a href="Managing_Products.php"><section class="Managing-Card" id="ManageProducts"><h4>Manage Products</h4><p>View, edit and add furniture products</p></section></a>
            <a href="Managing_Vendor.php"><section class="Managing-Card" id="ManageVendors"><h4>Manage Vendors</h4><p>View and manage furniture vendors</p></section></a>
            <a href="Managing_Users.php"><section class="Managing-Card" id="ManageUsers"><h4>Manage Users</h4><p>View and control user accounts</p></section></a>
            <a href="View_Transaction.php"><section class="Managing-Card" id="ViewTransaction"><h4>View Transactions</h4><p>Monitor purchase history and details</p></section></a>
        </section>
        <section class="welcome-section">
            <h2>Welcome, <?php echo htmlspecialchars($username); ?>!</h2>
            <p>
            Use the cards above to manage the platform. Keep track of users,
            inventory, vendors, and transactions efficiently.
            </p>
        </section>

    <?php else: ?>
        <section class="product-section">
        <?php
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) { 
                $detail_link = 'ProductDetail.php?id=' . htmlspecialchars($row['productID']);
                ?>
                <div class="product-card">
                  <img src="Asset/<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['productName']); ?>" />
                  
                  <h3><?php echo htmlspecialchars($row['productName']); ?></h3>
                  
                  <p><?php echo htmlspecialchars($row['description']); ?></p>
                  
                  <h4>Rp <?php echo number_format($row['price'], 0, ',', '.'); ?></h4>
                  
                  <a href="<?php echo $detail_link; ?>">
                      <button class="btn">View Details</button>
                  </a>
                  
                  </div>
                <?php
            }
        } else {
            echo "<p style='text-align:center; width:100%;'>No products found.</p>";
        }
        ?>
        </section>
    <?php endif; ?>

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

<?php 
if (isset($conn) && $conn->ping()) {
    $conn->close();
}
?>