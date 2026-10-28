<?php
session_start();
include("connect.php");

if (!isset($_SESSION['username'])) {
    header("Location: login.php"); 
    exit();
}

$current_admin_username = $_SESSION['username'];
$users_data = [];
$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_user'])) {
    $user_to_delete = mysqli_real_escape_string($conn, $_POST['user_to_delete']);

    if ($user_to_delete === $current_admin_username) {
        $error_message = "Error: You cannot delete your own admin account.";
    } else {
        $delete_query = "DELETE FROM users WHERE username = '$user_to_delete'";
        if (mysqli_query($conn, $delete_query)) {
            $success_message = "User '{$user_to_delete}' successfully deleted.";
            header("Location: Managing_Users.php?success=" . urlencode($success_message));
            exit();
        } else {
            $error_message = "Error deleting user: " . mysqli_error($conn);
        }
    }
}

$query = "SELECT username, email, gender, dob, role FROM users ORDER BY role ASC, username ASC";
$result = mysqli_query($conn, $query);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $users_data[] = $row;
    }
} else {
    $error_message = "Error fetching user data: " . mysqli_error($conn);
}

if (isset($_GET['success'])) {
    $success_message = htmlspecialchars($_GET['success']);
}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Manage Users - Admin Furniland</title>
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
                Hello, <?php echo htmlspecialchars($current_admin_username); ?>
            </a>
        </p>
        <a href="logout.php" class="merah">Log Out</a>
      </div>
    </nav>

    <header class="header-admin">
      <div>
        <h1>Manage Users</h1>
      </div>
    </header>

    <main class="table-container">
        <?php if ($error_message): ?>
            <p style="color: red; text-align: center;"><?php echo $error_message; ?></p>
        <?php endif; ?>
        <?php if ($success_message): ?>
            <p style="color: green; text-align: center;"><?php echo $success_message; ?></p>
        <?php endif; ?>

      <table class="table">
        <thead>
          <tr>
            <th>Username</th>
            <th>Email</th>
            <th>Gender</th>
            <th>Date of Birth</th>
            <th>Role</th>
            <th>Actions</th>
          </tr>
        </thead>

        <tbody>
          <?php if (empty($users_data)): ?>
            <tr>
                <td colspan="6" style="text-align: center;">No users found in the database.</td>
            </tr>
          <?php else: ?>
            <?php foreach ($users_data as $user): ?>
                <tr>
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td><?php echo htmlspecialchars($user['gender']); ?></td>
                    <td><?php echo htmlspecialchars($user['dob']); ?></td>
                    <td><?php echo htmlspecialchars($user['role']); ?></td>
                    <td>
                        <?php if ($user['username'] === $current_admin_username): ?>
                            <span class="current-admin">Current Admin</span>
                        <?php else: ?>
                            <form method="POST" action="Managing_Users.php" onsubmit="return confirm('Are you sure you want to delete user <?php echo htmlspecialchars($user['username']); ?>?');" style="display: inline;">
                                <input type="hidden" name="user_to_delete" value="<?php echo htmlspecialchars($user['username']); ?>">
                                <button type="submit" name="delete_user" class="delete-userbtn">Delete</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
          <?php endif; ?>
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