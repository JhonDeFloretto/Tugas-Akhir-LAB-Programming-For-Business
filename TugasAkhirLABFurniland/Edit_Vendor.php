<?php
session_start();
include("connect.php");

if (!isset($_SESSION['username'])) {
    header("Location: login.php"); 
    exit();
}

$username = $_SESSION['username'];
$error_msg = "";

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $get_vendor = mysqli_query($conn, "SELECT * FROM vendors WHERE vendorID = '$id'");
    $data = mysqli_fetch_assoc($get_vendor);
} else {
    header("Location: Managing_Vendor.php");
}

if (isset($_POST['EditVendor'])) {
    $vname = $_POST['EditVName'];
    $vlocation = $_POST['EditVLocation'];

    if (empty($vname) || empty($vlocation)) {
        $error_msg = "All fields must be filled.";
    } elseif (strlen($vname) > 20) {
        $error_msg = "Vendor Name maximum 20 characters.";
    } elseif (strlen($vlocation) > 100) {
        $error_msg = "Location maximum 100 characters.";
    } else {
        $query = "UPDATE vendors SET vendorName = '$vname', location = '$vlocation' WHERE vendorID = '$id'";
        if (mysqli_query($conn, $query)) {
            header("Location: Managing_Vendor.php");
            exit();
        }
    }
}
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

    <main>
      <form class="Form" id="Edit-Vendor" action="" method="post">
        <div class="isi">
          <h2>Edit Vendor</h2>
          <?php if($error_msg != "") echo "<p style='color:red; text-align:center;'>$error_msg</p>"; ?>
        </div>
        <div class="isi">
          <div class="form-section">
            <p>vendor Name</p>
            <input type="text" name="EditVName" id="IdEditVName" value="<?php echo $data['vendorName']; ?>" />
          </div>

          <div class="form-section">
            <p>Location</p>
            <input type="text" name="EditVLocation" id="IdEditVLocation" value="<?php echo $data['location']; ?>" />
          </div>

          <div class="form-section">
            <button type="submit" name="EditVendor">Update Vendor</button>
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