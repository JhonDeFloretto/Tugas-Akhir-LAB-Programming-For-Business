<?php
session_start();
include("connect.php");

if (!isset($_SESSION['username'])) {
    header("Location: login.php"); 
    exit();
}

$username = $_SESSION['username'];

if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    
    $check_query = "SELECT * FROM products WHERE vendorID = '$id'";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        echo "<script>alert('Vendor cannot be deleted because it is referenced in a product.'); window.location='Managing_Vendor.php';</script>";
    } else {
        $delete_query = "DELETE FROM vendors WHERE vendorID = '$id'";
        if (mysqli_query($conn, $delete_query)) {
            header("Location: Managing_Vendor.php");
        }
    }
}

$query = "SELECT * FROM vendors";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Home Admin Furniland</title>
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
                Hello, <?php echo $username; ?>
            </a>
        </p>
        <a href="logout.php" class="merah">Log Out</a>
      </div>
    </nav>


    <header class="header-admin">
      <div>
        <h1>Manage Vendor</h1>
      </div>
      <div class="vendor-add-btn-container">
        <a href="Add_Vendor.php" class="add-btn">+ Add Vendor</a>
      </div>
    </header>

    <main class="table-container">
      <table class="table">
        <thead class="vendor-thead">
          <tr class="vendor-thead-row">
            <th class="vendor-th">Vendor Name</th>
            <th class="vendor-th">Location</th>
            <th class="vendor-th">Actions</th>
          </tr>
        </thead>

        <tbody class="vendor-tbody">
          <?php while($row = mysqli_fetch_assoc($result)) { ?>
          <tr class="vendor-row">
            <td><?php echo $row['vendorName']; ?></td>
            <td><?php echo $row['location']; ?></td>
            <td class="actions">
              <a href="Edit_Vendor.php?id=<?php echo $row['vendorID']; ?>" class="Edit-btn" style="text-decoration:none; display:inline-block;">Edit</a>
              <a href="Managing_Vendor.php?delete_id=<?php echo $row['vendorID']; ?>" class="delete-btn" style="text-decoration:none; display:inline-block;" onclick="return confirm('Are you sure you want to delete this vendor?')">Delete</a>
            </td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
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