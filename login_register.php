<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require('connection.php');

// Function to display error alert and redirect
function showErrorAlert($message, $redirectUrl) {
    echo "<script>alert('$message'); window.location.href='$redirectUrl';</script>";
    exit();
}

// For login
if(isset($_POST['login'])) {
   $email_username = $_POST['email_username'];
   $password = $_POST['password'];
   
   // Using prepared statement to prevent SQL injection
   $query = "SELECT * FROM registered_user WHERE email=? OR username=?";
   $stmt = mysqli_prepare($con, $query);
   mysqli_stmt_bind_param($stmt, "ss", $email_username, $email_username);
   mysqli_stmt_execute($stmt);
   $result = mysqli_stmt_get_result($stmt);

   if($result && mysqli_num_rows($result) == 1) {
      $result_fetch = mysqli_fetch_assoc($result);
      // Debugging: Output retrieved password and password entered by the user
      echo "Retrieved Password: " . $result_fetch['Password'] . "<br>";
      echo "Entered Password: " . $password . "<br>";
      
      // Verify password
      if(password_verify($password, $result_fetch['Password'])) {
         // Password is correct, redirect to main.html
         header("Location: main.html");
         exit(); // Ensure that no further code is executed after redirection
      } else {
         echo "<script>alert('Incorrect password'); window.location.href='login_register.php';</script>";
         exit();
      }
   } else {
      echo "<script>alert('Email or Username Not Registered'); window.location.href='login_register.php';</script>";
      exit();
   }
}

// For registration
if(isset($_POST['register']))
{
    $fullname = $_POST['fullname'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    
    $user_exist_query = "SELECT * FROM registered_user WHERE `Username`=? OR `E-mail`=?";
    $stmt = mysqli_prepare($con, $user_exist_query);
    mysqli_stmt_bind_param($stmt, "ss", $username, $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if($result && mysqli_num_rows($result) > 0) {
        $result_fetch = mysqli_fetch_assoc($result);
        if($result_fetch['Username'] == $username) {
            showErrorAlert("$username - Username already taken", "index.php");
        } elseif ($result_fetch['E-mail'] == $email) {
            showErrorAlert("$email - E-mail already registered", "index.php");
        }
    } else {
        $query = "INSERT INTO registered_user (`Full Name`, `Username`, `E-mail`, `Password`) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "ssss", $fullname, $username, $email, $password);
        if(mysqli_stmt_execute($stmt)) {
            showErrorAlert("Registration successful", "index.php");
        } else {
            showErrorAlert("Cannot Run Query", "index.php");
        }
    }
}
?>
