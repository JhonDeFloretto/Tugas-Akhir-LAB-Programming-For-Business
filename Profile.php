<?php
session_start();
include("connect.php");

if (!isset($_SESSION['username'])) {
    header("Location: Login.php");
    exit();
}

$username = $_SESSION['username'];
$role = $_SESSION['role'];

$sql = "SELECT * FROM users WHERE username = '$username'";
$userResult = mysqli_query($conn, $sql);
$data = mysqli_fetch_assoc($userResult);

if (!$data) {
    session_destroy();
    header("Location: Login.php");
    exit();
}

$errors = [];
$password_errors = [];
$delete_errors = [];

if(isset($_POST['btnUpdateProfile'])){ 
    $old_username = $username;
    $new_username = mysqli_real_escape_string($conn, $_POST['Username']);
    $new_email    = mysqli_real_escape_string($conn, $_POST['Email']);
    $new_gender   = mysqli_real_escape_string($conn, $_POST['gender']);
    $new_dob      = mysqli_real_escape_string($conn, $_POST['DOB']);

    $dobdate = new DATETIME($new_dob);
    $today = new DATETIME();
    
    if (empty($new_username)) {$errors[] = "Nama wajib diisi.";}
    if (strlen($new_username) > 20 || strlen($new_username) < 4) {$errors[] = "Nama harus 4-20 karakter";}
    
    if(empty($new_email)){$errors[] = "Email wajib diisi.";}
    if(!str_ends_with($new_email, '@gmail.com')){$errors[] = "Email wajib diakhiri dengan @gmail.com .";}
    
    if ($new_username !== $old_username) {
        $check_user_query = "SELECT username FROM users WHERE username = '$new_username'";
        if (mysqli_num_rows(mysqli_query($conn, $check_user_query)) > 0) {
            $errors[] = "Username sudah digunakan oleh pengguna lain.";
        }
    }
    
    if (empty($new_dob)) {$errors[] = "DOB wajib diisi.";}
    if($dobdate >= $today){$errors[] = "DOB hanya boleh di waktu lampau.";}
    if (empty($new_gender)) {$errors[] = "Gender wajib diisi.";}

    if(empty($errors)){
        $update_profile_query = "
            UPDATE 
                users 
            SET 
                username = '$new_username',
                email = '$new_email', 
                gender = '$new_gender', 
                dob = '$new_dob' 
            WHERE 
                username = '$old_username'"; 

        $update_profile = mysqli_query($conn, $update_profile_query);
        if($update_profile){
            $_SESSION['username'] = $new_username;
            $username = $new_username;
            $data['username'] = $new_username;
            $data['email'] = $new_email;
            $data['gender'] = $new_gender;
            $data['dob'] = $new_dob;
            echo "<script>alert('Informasi profil berhasil diperbarui!'); window.location.href='Profile.php';</script>";
        } else {
            echo "<script>alert('Gagal memperbarui profil: " . mysqli_error($conn) . "');</script>";
        }
    }else{
        echo "<script>alert('Gagal Update profile:\\n" . implode("\\n", $errors) . "');</script>";
    }
}

if((isset($_POST['Cpassword']) && $_POST['Cpassword'] !== '') || (isset($_POST['Npassoword']) && $_POST['Npassoword'] !== '') || (isset($_POST['Conpassword']) && $_POST['Conpassword'] !== '')){
    
    $current_pass = $_POST['Cpassword'] ?? '';
    $new_pass     = $_POST['Npassoword'] ?? '';
    $confirm_pass = $_POST['Conpassword'] ?? '';

    $old_password = $data['password']; 

    if (empty($current_pass) || empty($new_pass) || empty($confirm_pass)) {
        $password_errors[] = "Semua kolom password wajib diisi.";
    } elseif ($current_pass!==$old_password) {
        $password_errors[] = "Password saat ini salah!";
    } elseif ($new_pass !== $confirm_pass) {
        $password_errors[] = "Password baru dan konfirmasi tidak cocok!";
    } elseif (strlen($new_pass) <8) { 
        $password_errors[] = "Password baru minimal 8 karakter!"; 
    }
    
    if(empty($password_errors)){
        $update_pass_query = "
            UPDATE 
                users 
            SET 
                password = '$new_pass' 
            WHERE 
                username = '$username'"; 

        if (mysqli_query($conn, $update_pass_query)) {
            $data['password'] = $new_pass; 
            echo "<script>alert('Password berhasil diperbarui!'); window.location.href='Profile.php';</script>";
        } else {
            echo "<script>alert('Gagal memperbarui password: " . mysqli_error($conn) . "');</script>";
        }
    } else {
         echo "<script>alert('Gagal Update Password:\\n" . implode("\\n", $password_errors) . "');</script>";
    }
}

if(isset($_POST['btnDelete'])){ 
    $confirm_pass = $_POST['password'] ?? ''; 
    $password = $data['password'];
    
    if (empty($confirm_pass)) {
        $delete_errors[] = "Password konfirmasi wajib diisi.";
    } elseif ($confirm_pass!==$password) {
        $delete_errors[] = "Password konfirmasi salah!";
    }

    if(empty($delete_errors)){
        $delete_query = "DELETE FROM users WHERE username = '$username'";

        if (mysqli_query($conn, $delete_query)) {
            session_destroy();
            echo "<script>alert('Akun Anda berhasil dihapus.'); window.location.href='Home.php';</script>";
            exit;
        } else {
            echo "<script>alert('Gagal menghapus akun: " . mysqli_error($conn) . "');</script>";
        }
    } else {
        echo "<script>alert('Gagal Hapus Akun:\\n" . implode("\\n", $delete_errors) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Profile - Furniland</title>
    <link rel="stylesheet" href="style.css" />
  </head>

  <body>
    <nav class="navbar">
      <div class="nav-kolom-left">
        <div class="nav-left">Furniland</div>
        <ul class="nav-menu">
          <?php if($role == "admin"): ?>
          <li><a href="Home.php" class="biru">Dashboard</a></li>
          <?php else: ?>
          <li><a href="Home.php" class="biru">Home</a></li>
          <li><a href="ProductCatalog.php" class="biru">Catalog</a></li>
          <?php endif; ?>
        </ul>
      </div>

      <div class="nav-right">
        <p>
          <a href="Profile.php" class="Hello-User">
            Hello, <?php echo htmlspecialchars($username); ?>
          </a>
        </p>

        <?php if($role == "member"): ?>
        <a href="Cart.php" class="biru">Cart</a>
        <a href="History.php" class="biru">History</a>
        <?php endif; ?>

        <a href="logout.php" class="merah">Log Out</a>
      </div>
    </nav>
    
    <header class="header">
      <h1>Your Profile</h1>
    </header>

    <main class="Update">
      <section class="Update-Profile-Information">
        <form class="Form" id="Form-Update-Profile" action="Profile.php" method="post">
          <div class="isi">
            <h3>Update Profile Information</h3>
          </div>
          <div class="isi">
            <div class="form-section">
              <p>Username</p>
              <input type="text" name="Username" id="IdUsername" value="<?php echo htmlspecialchars($data['username']); ?>" />
            </div>

            <div class="form-section">
              <p>Email</p>
               <input type="email" name="Email" id="IdEmail" value="<?php echo htmlspecialchars($data['email']); ?>"  />
            </div>

            <div class="form-section">
              <p>Gender</p>
               <select name="gender" id="idgender">
                <option value="Female" <?php if($data['gender'] == 'Female') echo 'selected'; ?>>Female</option>
                <option value="Male" <?php if($data['gender'] == 'Male') echo 'selected'; ?>>Male</option>
              </select>
            </div>

            <div class="form-section">
              <p>Date Of Birth</p>
               <input type="date" name="DOB" id="IdDOB" value="<?php echo $data['dob']; ?>" required />
            </div>

            <div class="form-section">
              <button type="submit" name="btnUpdateProfile">Save Change</button>
            </div>
          </div>
        </form>
      </section>

      <section class="Update-Password">
         <form class="Form" id="Form-Update-Password" action="Profile.php" method="post">
          <div class="isi">
            <h3>Update Password</h3>
          </div>
          <div class="isi">
            <div class="form-section">
              <p>Current Password</p>
              <input type="password" name="Cpassword" id="IdCpassword" />
            </div>

            <div class="form-section">
              <p>New Password</p>
              <input type="password" name="Npassoword" id="IdNpassoword" />
            </div>

            <div class="form-section">
              <p>Confirm Password</p>
              <input type="password" name="Conpassword" id="IdConpassword" />
            </div>

            <div class="form-section">
              <button type="submit">Update Password</button>
            </div>
          </div>
        </form>
      </section>

      <section class="delete-account">
        <form class="Form" method="post" action="Profile.php">
          <h2>Delete Account</h2>
          <p class="warning-text">Once deleted, your account cannot be recovered.</p>
          <div class="form-section">
            <label for="confirm-password">Confirm Password</label><br />
            <input type="password" id="confirm-password" name="password" required />
          </div>
          <div class="form-section">
            <button type="submit" name="btnDelete" class="delete-btn-merah">Delete Account</button>
          </div>
        </form>
      </section>
    </main>

    <footer class="footer">
      <p>© 2025 Furniland. All rights reserved.</p>
    </footer>
  </body>
</html>