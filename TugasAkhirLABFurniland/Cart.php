<?php
session_start();
include("connect.php");

if (!isset($_SESSION['username'])) {
    header("Location: Login.php");
    exit();
}

$username = $_SESSION['username'];
$cart_items = [];
$product_ids = [];
$checkout_error = '';

if (isset($_GET['remove_id'])) {
    $remove_id = (int)$_GET['remove_id'];
    if (isset($_SESSION['cart'][$remove_id])) {
        unset($_SESSION['cart'][$remove_id]); 
        header("Location: Cart.php"); 
        exit();
    }
}


$final_total = 0; 
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    $product_ids = array_keys($_SESSION['cart']);
    
    $id_list = implode(",", array_map('intval', $product_ids));

    $product_query = "SELECT productID, productName, price FROM products WHERE productID IN ($id_list)";
    $product_result = mysqli_query($conn, $product_query);

    if ($product_result) {
        while ($product_row = mysqli_fetch_assoc($product_result)) {
            $productID = $product_row['productID'];
            $quantity = $_SESSION['cart'][$productID];
            $subtotal = $product_row['price'] * $quantity;
            $final_total += $subtotal; 

            $cart_items[] = [
                'id' => $productID,
                'name' => $product_row['productName'],
                'price' => $product_row['price'],
                'quantity' => $quantity,
                'subtotal' => $subtotal
            ];
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['checkout']) && !empty($cart_items)) {
    $user_query = "SELECT userID FROM users WHERE username = '$username'";
    $user_result = mysqli_query($conn, $user_query);
    if ($user_result && $user_row = mysqli_fetch_assoc($user_result)) {
        $userID = $user_row['userID'];

        $insert_transaction_query = "INSERT INTO transactions (userID, transactionDate, totalPrice) VALUES ('$userID', NOW(), '$final_total')";
        if (mysqli_query($conn, $insert_transaction_query)) {
            $transactionID = mysqli_insert_id($conn); 

            $success_details = true;
            foreach ($cart_items as $item) {
                $productID = $item['id'];
                $quantity = $item['quantity'];
                $subtotal = $item['subtotal'];
                
                $insert_detail_query = "INSERT INTO transaction_details (transactionID, productID, quantity, subtotal) 
                                        VALUES ('$transactionID', '$productID', '$quantity', '$subtotal')";
                if (!mysqli_query($conn, $insert_detail_query)) {
                    $success_details = false;
                    break; 
                }
            }

            if ($success_details) {
                unset($_SESSION['cart']);
                header("Location: History.php?success=1"); 
                exit();
            }
        } else {
            $checkout_error = "Failed to create transaction record: " . mysqli_error($conn);
        }

    } else {
        $checkout_error = "Error: User ID not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Your Cart Furniland</title>
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
        <a href="History.php" class="biru">History</a>
        <a href="logout.php" class="merah">Log Out</a>
      </div>
    </nav>

    <header class="header">
      <h1>Your Cart</h1>
      <?php if (!empty($checkout_error)) { ?>
          <p style="color: red;"><?php echo $checkout_error; ?></p>
      <?php } ?>
      <?php if (empty($cart_items)) { ?>
          <i id="If Your Cart is Empty">your cart is empty</i>
      <?php } ?>
    </header>

    <main>
      <section class="cart-section">
        <?php if (!empty($cart_items)) { ?>
            <table class="cart-table">
              <thead>
                <tr>
                  <th>Product</th>
                  <th>Qty</th>
                  <th>Subtotal</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($cart_items as $item) { ?>
                    <tr>
                      <td class="product-name"><?php echo htmlspecialchars($item['name']); ?></td>
                      <td class="quantity"><?php echo $item['quantity']; ?></td>
                      <td class="price">Rp <?php echo number_format($item['subtotal'], 0, ',', '.'); ?></td>
                      <td class="action-cell">
                        <a href="Cart.php?remove_id=<?php echo $item['id']; ?>">
                            <button class="remove-btn">Remove</button>
                        </a>
                      </td>
                    </tr>
                <?php } ?>
              </tbody>
              </table>

            <form method="POST" action="Cart.php" class="checkout-section">
              <button type="submit" name="checkout" class="checkout-btn">Checkout</button>
            </form>
            
        <?php } ?>
      </section>
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