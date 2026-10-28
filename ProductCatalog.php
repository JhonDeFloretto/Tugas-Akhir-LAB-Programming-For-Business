<?php
session_start();
include("connect.php"); 



if (!isset($_SESSION['username'])) {
    header("Location: Login.php"); 
    exit();
}
$username = $_SESSION['username'];


$vendor_query = "SELECT vendorID, vendorName FROM vendors ORDER BY vendorName ASC";
$vendor_result = mysqli_query($conn, $vendor_query);

$search_query = isset($_GET['search_query']) ? mysqli_real_escape_string($conn, $_GET['search_query']) : '';
$vendor_filter = isset($_GET['vendor_filter']) ? mysqli_real_escape_string($conn, $_GET['vendor_filter']) : '';
$sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'productID_desc';

$where_clauses = [];
if (!empty($search_query)) {
    $where_clauses[] = "(p.productName LIKE '%$search_query%' OR p.description LIKE '%$search_query%')";
}
if (!empty($vendor_filter)) {
    $where_clauses[] = "p.vendorID = '$vendor_filter'";
}

$where_sql = count($where_clauses) > 0 ? ' WHERE ' . implode(' AND ', $where_clauses) : '';

$order_by_sql = "p.productID DESC"; 
switch ($sort_by) {
    case 'price_asc':
        $order_by_sql = "p.price ASC";
        break;
    case 'price_desc':
        $order_by_sql = "p.price DESC";
        break;
    case 'name_asc':
        $order_by_sql = "p.productName ASC";
        break;
}

$main_product_query = "SELECT p.*, v.vendorName
                       FROM products p
                       JOIN vendors v ON p.vendorID = v.vendorID
                       $where_sql
                       ORDER BY $order_by_sql";

$result = mysqli_query($conn, $main_product_query);

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Product Catalog Furniland</title>
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
      <h1>Product Catalog</h1>
      <p>Browse, Filter, and sort furniture</p>
    </header>

    <main class="Catalog-page-main">
      <form method="GET" action="ProductCatalog.php">
        <section class="search-section">
          <section class="product-Vendor-and-Sort">
            <select name="vendor_filter" id="IdVendors">
              <option value="">All Vendors</option>
              <?php
              if (mysqli_num_rows($vendor_result) > 0) {
                  mysqli_data_seek($vendor_result, 0); 
                  while ($vendor_row = mysqli_fetch_assoc($vendor_result)) {
                      $selected = ($vendor_row['vendorID'] == $vendor_filter) ? 'selected' : '';
                      echo '<option value="' . $vendor_row['vendorID'] . '" ' . $selected . '>' . htmlspecialchars($vendor_row['vendorName']) . '</option>';
                  }
              }
              ?>
            </select>
            <select name="sort_by" id="IdSort">
              <option value="">Sort by</option>
              <option value="price_asc" <?php if ($sort_by == 'price_asc') echo 'selected'; ?>>Price: Low to High</option>
              <option value="price_desc" <?php if ($sort_by == 'price_desc') echo 'selected'; ?>>Price: High to Low</option>
              <option value="name_asc" <?php if ($sort_by == 'name_asc') echo 'selected'; ?>>Name: A-Z</option>
            </select>
          </section>
          <section class="Direct-Search-Product">
            <input
              type="text"
              name="search_query"
              id="IdSearchProduct"
              placeholder="Search Product"
              value="<?php echo htmlspecialchars($search_query); ?>"
            />
            <button type="submit" class="Btn-Apply">Apply</button>
          </section>
        </section>
      </form>
      
      <section class="product-section">
        <?php
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                ?>
                <div class="product-card">
                  <img src="Asset/<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['productName']); ?>" />         
                  <h3><?php echo htmlspecialchars($row['productName']); ?></h3>
                  <p><?php echo htmlspecialchars($row['description']); ?></p>
                  <h4>Rp <?php echo number_format($row['price'], 0, ',', '.'); ?></h4>
                  <a href="ProductDetail.php?id=<?php echo htmlspecialchars($row['productID']); ?>">
                      <button class="btn">View Details</button>
                  </a>
                  </div>
                <?php
            }
        } else {
            echo "<p style='text-align:center; width:100%;'>No products found matching your criteria.</p>";
        }
        ?>
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

