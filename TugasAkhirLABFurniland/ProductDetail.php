<?php
session_start();
include("connect.php");

$is_logged_in = isset($_SESSION['username']);
$role = $is_logged_in ? $_SESSION['role'] : 'guest';
$username = $is_logged_in ? $_SESSION['username'] : 'Guest';
$productID = null;
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_to_cart'])) {
    
    if (!$is_logged_in) {
        header("Location: Login.php");
        exit();
    }
    
    $product_id_to_add = mysqli_real_escape_string($conn, $_POST['product_id']);
    $quantity = (int)$_POST['quantity'];
    
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    if ($quantity > 0) {
        if (isset($_SESSION['cart'][$product_id_to_add])) {
            $_SESSION['cart'][$product_id_to_add] += $quantity;
        } else {
            $_SESSION['cart'][$product_id_to_add] = $quantity;
        }
        
        header("Location: Cart.php"); 
        exit();
    } else {
        $error_message = "Quantity must be at least 1.";
    }
}


if (isset($_GET['id'])) {
    $productID = mysqli_real_escape_string($conn, $_GET['id']);

    $query = "SELECT p.*, v.vendorName, v.Location
              FROM products p
              JOIN vendors v ON p.vendorID = v.vendorID
              WHERE p.productID = '$productID'";

    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $product = mysqli_fetch_assoc($result);
    } else {
        header("Location: Home.php"); 
        exit();
    }
} else {
    header("Location: Home.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Detail Produk Furniland</title>
    <link rel="stylesheet" href="style.css" />
  </head>

  <body>
    <nav class="navbar">
      <div class="nav-kolom-left">
        <div class="nav-left">Furniland</div>
        <ul class="nav-menu">
          <li><a href="Home.php" class="biru">Home</a></li>
          <?php if ($role === 'member'): ?>
            <li><a href="ProductCatalog.php" class="biru">Catalog</a></li>
          <?php endif; ?>
        </ul>
      </div>
      <div class="nav-right">
        <?php if (!$is_logged_in): ?>
            <a href="Login.php" class="biru">Login</a>
        <?php else: ?>
            <p>
                <a href="Profile.php" class="Hello-User">
                    Hello, <?php echo htmlspecialchars($username); ?>
                </a>
            </p>
            <?php if ($role === 'member'): ?>
                <a href="Cart.php" class="biru">Cart</a>
                <a href="History.php" class="biru">History</a>
            <?php endif; ?>
            <a href="logout.php" class="merah">Log Out</a>
        <?php endif; ?>
      </div>
    </nav>

    <header class="header"></header>

    <main>
      <div class="Details-Product">
        <div class="Details-Product-G">
            <img
            src="Asset/<?php echo htmlspecialchars($product['image']); ?>"
            alt="<?php echo htmlspecialchars($product['productName']); ?>"
            class="Details-Product-Gambar"
          />
        </div>
        <div class="Detail-Product-Description">
            <h1><?php echo htmlspecialchars($product['productName']); ?></h1>
          <p>
            <?php echo htmlspecialchars($product['description']); ?>
          </p>
          <h3>Rp <?php echo number_format($product['price'], 0, ',', '.'); ?></h3>
          <p>Vendor: <?php echo htmlspecialchars($product['vendorName']); ?></p>
          <p>Location: <?php echo htmlspecialchars($product['Location']); ?></p>
          
          <form method="POST" action="ProductDetail.php?id=<?php echo $product['productID']; ?>">
              <?php if (!empty($error_message)) { echo "<p style='color:red;'>$error_message</p>"; } ?>
              
              <div class="Quantity-Description">
                <p>Quantity:</p>
                <input type="number" name="quantity" id="Quantity" class="Quantity" value="1" min="1" required />
              </div>
              
              <input type="hidden" name="product_id" value="<?php echo $product['productID']; ?>" />
              <br>
              <button type="submit" name="add_to_cart" class="btn">Add to Cart</button>
          </form>

        </div>
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

  </body>
</html>
<?php 
if (isset($conn) && $conn->ping()) {
    $conn->close();
}
?>