<?php
session_start();
include("connect.php");

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header("Location: Home.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login Page Furniland</title>
    <link rel="stylesheet" href="Style.css" />
  </head>
  <body>
    <nav class="navbar">
      <div class="nav-kolom-left">
        <div class="nav-left">Furniland</div>
        <ul class="nav-menu">
          <li><a href="Home.php">Home</a></li>
        </ul>
      </div>
      <div class="nav-right"><a href="Login.php" class="biru">Login</a></div>
    </nav>
    
    <main>
      <form action="Register_Login.php" method="post" id="form-login" class="Form">
        <h1>Login</h1>
        <div class="isi">
          <div class="form-section">
              <p>Email</p>
              <input type="email" name="email" id="IdEmail" required />
          </div>

          <div class="form-section">
              <p>Password</p>
              <input type="password" name="password" id="IdPassword" required />
          </div>

          <div class="checkbox">
            <input type="checkbox" name="remember" id="rememberMe" />
            <p>Remember me</p>
          </div>

          <div class="form-section">
            <button type="submit" name="btnlogin" id="btnlogin">Login</button>
          </div>

          <div class="form-section">
            <p>Don't have an account? <a href="Register.php">Register Here</a></p>
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