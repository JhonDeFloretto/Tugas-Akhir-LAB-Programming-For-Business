<?php
session_start();
include("connect.php");

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: Login.php"); 
    exit();
}

$username = $_SESSION['username'];

if (isset($_GET['delete'])) {
    $productID = $_GET['delete'];
    
    $checkQuery = "SELECT * FROM transaction_details WHERE productID = '$productID'";
    $checkResult = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($checkResult) > 0) {
        echo "<script>alert('Produk tidak bisa dihapus karena sudah ada dalam riwayat transaksi!'); window.location.href='Managing_Products.php';</script>";
    } else {
        $deleteQuery = "DELETE FROM products WHERE productID = '$productID'";
        if (mysqli_query($conn, $deleteQuery)) {
            echo "<script>alert('Produk berhasil dihapus'); window.location.href='Managing_Products.php';</script>";
        }
    }
}

$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$vendorFilter = isset($_GET['vendor']) ? mysqli_real_escape_string($conn, $_GET['vendor']) : '';

$vendorList = mysqli_query($conn, "SELECT * FROM vendors");

$sql = "SELECT p.*, v.vendorName FROM products p 
        LEFT JOIN vendors v ON p.vendorID = v.vendorID 
        WHERE p.productName LIKE '%$search%'";

if ($vendorFilter != '') {
    $sql .= " AND p.vendorID = '$vendorFilter'";
}

$sql .= " ORDER BY p.productID";
$productResult = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Manage Products - Furniland Admin</title>
    <link rel="stylesheet" href="Style.css" />
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
        <h1>Manage Products</h1>
      </div>
    </header>

    <main class="table-container">
      <section class="product-header">
        <form method="GET" action="" style="display: flex; gap: 10px; align-items: center;">
            <input type="text" name="search" class="smproduct" placeholder="Search product name..." value="<?php echo htmlspecialchars($search); ?>"/>
            <select name="vendor" class="vendor-filter" onchange="this.form.submit()">
              <option value="">All Vendors</option>
              <?php while($v = mysqli_fetch_assoc($vendorList)): ?>
                <option value="<?php echo $v['vendorID']; ?>" <?php echo ($vendorFilter == $v['vendorID']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($v['vendorName']); ?>
                </option>
              <?php endwhile; ?>
            </select>
        </form>

        <a href="Add_Product.php" class="add-btn">+ Add Product</a>
      </section>

      <table class="table" id="Idtable-Product">
        <thead>
          <tr>
            <th>ID</th>
            <th>Image</th>
            <th>Name</th>
            <th>Price</th>
            <th>Vendor</th>
            <th>Actions</th>
          </tr>
        </thead>

        <tbody>
          <?php if(mysqli_num_rows($productResult) > 0): ?>
            <?php while($row = mysqli_fetch_assoc($productResult)): ?>
              <tr>
                <td><?php echo $row['productID']; ?></td>
                <td><img src="Asset/<?php echo $row['image']; ?>" class="product-img" alt="img" onerror="this.src='img/placeholder.jpg'" /></td>
                <td><?php echo htmlspecialchars($row['productName']); ?></td>
                <td>Rp <?php echo number_format($row['price'], 0, ',', '.'); ?></td>
                <td><?php echo htmlspecialchars($row['vendorName']); ?></td>
                <td class="actions">
                  <a href="Edit_Product.php?id=<?php echo $row['productID']; ?>" class="Edit-btn" style="text-decoration:none;">Edit</a>
                  <a href="Managing_Products.php?delete=<?php echo $row['productID']; ?>" 
                     class="delete-btn" style="text-decoration:none;" 
                     onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">Delete</a>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr>
                <td colspan="6" style="text-align:center;">No products found.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </main>

    <footer class="footer">
      <p>© 2025 Furniland. All rights reserved.</p>
    </footer>

    <script src="script.js"></script>
  </body>
</html>