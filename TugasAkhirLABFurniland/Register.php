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
    <title>Registration Page</title>
    <link rel="stylesheet" href="Style.css" />
  </head>
  <body>
    <nav class="navbar">
      <div class="nav-kolom-left">
        <div class="nav-left">Furniland</div>
        <ul class="nav-menu">
          <li><a href="Home.php" class="biru">Home</a></li>
        </ul>
      </div>
      <div class="nav-right">
        <a href="Login.php" class="biru">Login</a>
      </div>
    </nav>
    <main>
      <form action="Register_Login.php" method="post" id="form-Register" class="Form">
          <h1>Register</h1>
          <div class="isi">
              <div class="form-section">
                  <p>Username</p>
                  <input type="text" name="IdUsername" id="IdUsername" required />
              </div>
      
              <div class="form-section">
                  <p>Email</p>
                  <input type="email" name="Idemail" id="IdEmail" required />
              </div>
      
              <div class="form-section">
                  <p>Password</p>
                  <input type="password" name="Idpassword" id="IPassword" required />
              </div>
      
              <div class="form-section">
                  <p>Re Enter Password</p>
                  <input type="password" name="Cpassword" id="ConfirmIdPassword" required />
              </div>
      
              <div class="form-section">
                  <p>Gender</p>
                  <div class="Gender">
                      <div><input type="radio" name="Idgender" value="Male" required /> Male</div>
                      <div><input type="radio" name="Idgender" value="Female" required /> Female</div>
                  </div>
              </div>
      
              <div class="form-section">
                  <p>Date Of Birth</p>
                  <input type="date" name="IdDOB" id="IdDOB" required />
              </div>
      
              <div class="form-section">
                  <button type="submit" name="btnRegister">Register</button>
              </div>
      
              <div class="form-section">
                  <p>Already Have an Account? <a href="Login.php">Login Here</a></p>
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