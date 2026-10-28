<?php
session_start();
if (isset($_COOKIE['user_id'])) {
    setcookie('user_id', '', time() - 3600, "/");
}
session_destroy();
header("location: Home.php");
exit();
?>