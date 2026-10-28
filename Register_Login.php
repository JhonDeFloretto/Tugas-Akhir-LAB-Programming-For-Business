<?php
include 'connect.php';
session_start(); 

function validateDOB($dob) {
    if (empty($dob)) return false;
    $dob_timestamp = strtotime($dob);
    $current_timestamp = time();
    return ($dob_timestamp !== false && $dob_timestamp < $current_timestamp);
}

if(isset($_POST['btnRegister'])){
    $username = $conn->real_escape_string($_POST['IdUsername']);
    $email = $conn->real_escape_string($_POST['Idemail']);
    $password = $conn->real_escape_string($_POST['Idpassword']); 
    $cpassword = $_POST['Cpassword'];
    $gender = $conn->real_escape_string($_POST['Idgender'] ?? '');
    $dob = $conn->real_escape_string($_POST['IdDOB']);
    $role = 'member';
    
    $errors = [];

    if(empty($username) || strlen($username) < 4 || strlen($username) > 20){
        $errors[] = 'Username must be 4-20 characters long!';
    } else {
        $checkUsername = "SELECT * FROM users WHERE username='$username'";
        if($conn->query($checkUsername)->num_rows > 0){
            $errors[] = 'Username already exists!';
        }
    }

    if(!filter_var($email, FILTER_VALIDATE_EMAIL) || substr($email, -10) !== '@gmail.com'){
        $errors[] = 'Email must be a valid @gmail.com address!';
    } else {
        $checkEmail = "SELECT * FROM users WHERE email='$email'";
        if($conn->query($checkEmail)->num_rows > 0){
            $errors[] = 'Email already exists!';
        }
    }

    if(strlen($password) < 8){
        $errors[] = 'Password must be at least 8 characters long!';
    }

    if($password !== $cpassword){
        $errors[] = 'Passwords do not match!';
    }

    if(!in_array($gender, ['Male', 'Female'])){
        $errors[] = 'Gender selection is invalid!';
    }

    if(!validateDOB($dob)){
        $errors[] = 'Date of birth is invalid or in the future!';
    }

    if(empty($errors)){
        $sql = "INSERT INTO users (username, email, password, gender, dob, role) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss", $username, $email, $password, $gender, $dob, $role);

        if ($stmt->execute()) {
            $_SESSION['role'] = $role;
            $_SESSION['username'] = $username;
            $_SESSION['logged_in'] = true;
            
            echo "<script>alert('Registration Successful! Welcome, " . $username . ".');</script>";
            
            echo "<script>window.location.href='ProductCatalog.php';</script>";
            exit();

        } else {
            echo "<script>alert('Registration Failed: " . $conn->error . "'); window.history.back();</script>";
        }
        $stmt->close();
    } else {
        $error_message = implode("\\n", $errors);
        echo "<script>alert('" . $error_message . "'); window.history.back();</script>";
    }
}


if(isset($_POST['btnlogin'])){
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];
    $remember_me = isset($_POST['remember']);

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        if ($password === $row['password']) {
            $_SESSION['role'] = $row['role'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['logged_in'] = true;
            
            if ($remember_me) {
                $cookie_name = "user_id";
                $cookie_value = $row['userID'];
                $expiry = time() + (7 * 24 * 60 * 60);
                setcookie($cookie_name, $cookie_value, $expiry, "/");
            }
            
            echo "<script>alert('Login Successful! Welcome " . $row['username'] . "');</script>";
            
            echo "<script>window.location.href='Home.php';</script>";

            exit();
        } else {
            echo "<script>alert('Wrong Credentials! Password incorrect.'); window.history.back();</script>"; 
        }
    } else {
        echo "<script>alert('Wrong Credentials! Email not found.'); window.history.back();</script>"; 
    }
    $stmt->close();
}

if (!isset($_SESSION['logged_in']) && isset($_COOKIE['user_id'])) {
    $user_id = $_COOKIE['user_id'];
    
    $sql = "SELECT * FROM users WHERE userID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        $_SESSION['role'] = $row['role'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['logged_in'] = true;
        
        $expiry = time() + (7 * 24 * 60 * 60);
        setcookie("user_id", $row['userID'], $expiry, "/");
                header("Location: Home.php");
        exit();
    }
    $stmt->close();
}

if(isset($conn) && $conn->ping()) {
    $conn->close();
}
?>