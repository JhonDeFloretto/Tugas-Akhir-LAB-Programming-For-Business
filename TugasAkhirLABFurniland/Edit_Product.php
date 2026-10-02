<?php
session_start();
include("connect.php");

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: Login.php"); 
    exit();
}

$username = $_SESSION['username'];
$productID = isset($_GET['id']) ? (int)$_GET['id'] : 0; 

if ($productID > 0) {
    $productQuery = mysqli_query($conn, "SELECT * FROM products WHERE productID='$productID'");
    if (mysqli_num_rows($productQuery) == 0) {
        echo "<script>alert('Product not found.'); window.location.href='Managing_Products.php';</script>";
        exit();
    }
    $product = mysqli_fetch_assoc($productQuery);
} else {
    header("Location: Managing_Products.php");
    exit();
}

if (isset($_POST['EditProduct'])) {
    $name = mysqli_real_escape_string($conn, isset($_POST['PName']) ? $_POST['PName'] : '');
    $desc = mysqli_real_escape_string($conn, isset($_POST['PDescription']) ? $_POST['PDescription'] : '');
    $price = isset($_POST['PPrice']) ? $_POST['PPrice'] : 0;
    $vendorID = isset($_POST['vendor']) ? $_POST['vendor'] : 0;
    
    $oldImage = mysqli_real_escape_string($conn, isset($_POST['OldImageName']) ? $_POST['OldImageName'] : ''); 
    
    $errors = [];
    if (strlen($name) < 3 || strlen($name) > 30) $errors[] = "Product Name must be 3-30 characters.";
    if (empty($desc)) $errors[] = "Description must be filled.";
    if (!is_numeric($price) || $price <= 0) $errors[] = "Price must be a number greater than 0.";

    $imageName = $oldImage; 
    if (isset($_FILES['productImage']) && $_FILES['productImage']['error'] == 0) {
        $imageName = $_FILES['productImage']['name'];
        $imageTmp = $_FILES['productImage']['tmp_name'];
        $imageExt = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'png', 'jpeg'];
        
        // Escape gambar baru (juga penting)
        $imageName = mysqli_real_escape_string($conn, $imageName);

        if (!in_array($imageExt, $allowed)) {
            $errors[] = "Image must be a valid .jpg or .png format.";
        } else {
            if (file_exists("Asset/" . $oldImage)) {
                unlink("Asset/" . $oldImage);
            }
            $uploadPath = "Asset/" . $imageName;
            move_uploaded_file($imageTmp, $uploadPath);
        }
    }
    
    if (empty($errors)) {
        $sql = "UPDATE products SET 
                productName='$name', 
                description='$desc', 
                price='$price', 
                image='$imageName', 
                vendorID='$vendorID' 
                WHERE productID='$productID'";
        
        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('Product Updated Successfully!'); window.location.href='Managing_Products.php';</script>";
            exit();
        } else {
            echo "<script>alert('Error updating product: " . mysqli_error($conn) . "');</script>";
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
    <title>Edit Product - Furniland Admin</title>
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
          <h2>Edit Product: <?php echo htmlspecialchars($product['productName']); ?></h2>
        </div>
        <div class="isi">
          <input type="hidden" name="OldImageName" value="<?php echo htmlspecialchars($product['image']); ?>">
          
          <div class="form-section">
            <p>Product Name</p>
            <input type="text" name="PName" id="IdPName" required value="<?php echo htmlspecialchars($product['productName']); ?>" />
          </div>

          <div class="form-section">
            <p>Description</p>
            <textarea name="PDescription" id="IdPDescription" required><?php echo htmlspecialchars($product['description']); ?></textarea>
          </div>

          <div class="form-section">
            <p>Price</p>
            <input type="number" name="PPrice" id="IdPPrice" required value="<?php echo htmlspecialchars($product['price']); ?>" />
          </div>

          <div class="form-section">
            <label for="vendor">Vendor</label>
            <select id="vendor" name="vendor" required>
              <?php 
              while($v = mysqli_fetch_assoc($vendorResult)): 
                $selected = ($v['vendorID'] == $product['vendorID']) ? 'selected' : '';
              ?>
                <option value="<?php echo $v['vendorID']; ?>" <?php echo $selected; ?>><?php echo htmlspecialchars($v['vendorName']); ?></option>
              <?php endwhile; ?>
            </select>
          </div>

          <div class="form-section">
            <p>Current Image: <?php echo htmlspecialchars($product['image']); ?></p>
            <label for="productImage">Change Product Image (.jpg / .png) - Optional</label>
            <input type="file" id="productImage" name="productImage" />
          </div>

          <div class="form-section">
            <button type="submit" name="EditProduct">Update Product</button>
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