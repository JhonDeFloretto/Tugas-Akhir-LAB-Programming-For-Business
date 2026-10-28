<?php
session_start();
include("connect.php");

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: Login.php"); 
    exit();
}

$username = $_SESSION['username'];

if (isset($_POST['AddProduct'])) {
    $name = mysqli_real_escape_string($conn, $_POST['PName']);
    $desc = mysqli_real_escape_string($conn, $_POST['PDescription']);
    $price = $_POST['PPrice'];
    $vendorID = $_POST['vendor'];
    
    $errors = [];
    if (strlen($name) < 3 || strlen($name) > 30) $errors[] = "Product Name must be 3-30 characters.";
    if (empty($desc)) $errors[] = "Description must be filled.";
    if (!is_numeric($price) || $price <= 0) $errors[] = "Price must be a number greater than 0.";

    $imageName = $_FILES['productImage']['name'];
    $imageTmp = $_FILES['productImage']['tmp_name'];
    $imageExt = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));
    $allowed = ['jpg', 'png'];

    $imageName = mysqli_real_escape_string($conn, $imageName);

    if (!in_array($imageExt, $allowed)) $errors[] = "Image must be a valid .jpg or .png format.";

    if (empty($errors)) {
        $uploadPath = "Asset/" . $imageName;
        move_uploaded_file($imageTmp, $uploadPath);

        $sql = "INSERT INTO products (productName, description, price, image, vendorID) 
                VALUES ('$name', '$desc', '$price', '$imageName', '$vendorID')";
        
        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('Product Added Successfully!'); window.location.href='Managing_Products.php';</script>";
            exit();
        }
    } else {
        $msg = implode("\\n", $errors);
        echo "<script>alert('$msg');</script>";
    }
}

$vendorResult = mysqli_query($conn, "SELECT * FROM vendors");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product - Furniland Admin</title>
    <link rel="stylesheet" href="Style.css">
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
        <p><a href="Profile.php" class="Hello-User">Hello, <?php echo $username; ?></a></p>
        <a href="logout.php" class="merah">Log Out</a>
      </div>
    </nav>

    <main>
      <form class="Form" action="" method="post" enctype="multipart/form-data">
        <div class="isi">
          <h2>Add New Product</h2>
        </div>
        <div class="isi">
          <div class="form-section">
            <p>Product Name</p>
            <input type="text" name="PName" id="IdPName" required />
          </div>

          <div class="form-section">
            <p>Description</p>
            <textarea name="PDescription" id="IdPDescription" required></textarea>
          </div>

          <div class="form-section">
            <p>Price</p>
            <input type="number" name="PPrice" id="IdPPrice" required />
          </div>

          <div class="form-section">
            <label for="vendor">Vendor</label>
            <select id="vendor" name="vendor" required>
              <?php while($v = mysqli_fetch_assoc($vendorResult)): ?>
                <option value="<?php echo $v['vendorID']; ?>"><?php echo $v['vendorName']; ?></option>
              <?php endwhile; ?>
            </select>
          </div>

          <div class="form-section">
            <label for="productImage">Product Image (.jpg / .png)</label>
            <input type="file" id="productImage" name="productImage" required />
          </div>

          <div class="form-section">
            <button type="submit" name="AddProduct">Add Product</button>
          </div>
        </div>
      </form>
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