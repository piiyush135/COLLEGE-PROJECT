<?php 
require('connection.php'); 

if(isset($_POST['login'])) {
   $email_username = $_POST['email_username'];
   $password = $_POST['password'];
   
   // Check if username and password are not empty
   if(empty($email_username) || empty($password)) {
      echo "<script>alert('Please enter both email/username and password'); window.location.href='login_register.php';</script>";
      exit();
   } else {
      // Using prepared statement to prevent SQL injection
      $query = "SELECT * FROM registered_user WHERE email=? OR username=?";
      $stmt = mysqli_prepare($con, $query);
      mysqli_stmt_bind_param($stmt, "ss", $email_username, $email_username);
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);

      if($result && mysqli_num_rows($result) == 1) {
         $result_fetch = mysqli_fetch_assoc($result);
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
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User - Login and Register</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  
  <header>
    <h2>E- Learning</h2>
    <nav>
      <a href="main.html">HOME</a>
      <a href="#">BLOG</a>
      <a href="contact.html">CONTACT</a>
      <a href="about us.html">ABOUT</a>
    </nav>
    <div class='sign-in-up'>
      <button type='button' onclick="popup('login-popup')">LOGIN</button>
      <button type='button' onclick="popup('register-popup')">REGISTER</button>
    </div>
  </header>

  <div class="popup-container" id="login-popup">
    <div class="popup">
      <form method="POST" action="">
        <h2>
          <span>USER LOGIN</span>
          <button type="reset" onclick="popup('login-popup')">X</button>
        </h2>
        <input type="text" placeholder="E-mail or Username" name="email_username">
        <input type="password" placeholder="Password" name="password">
        <button type="submit" class="login-btn" name="login">LOGIN</button>
      </form>
    </div>
  </div>

  <div class="popup-container" id="register-popup">
    <div class="register popup">
      <form method="POST" action="login_register.php">
        <h2>
          <span>USER REGISTER</span>
          <button type="reset" onclick="popup('register-popup')">X</button>
        </h2>
        <input type="text" placeholder="Full Name" name="fullname">
        <input type="text" placeholder="Username" name="username">
        <input type="email" placeholder="E-mail" name="email">
        <input type="password" placeholder="Password" name="password">
        <button type="submit" class="register-btn" name="register">REGISTER</button>
      </form>
    </div>
  </div>

  <script>
    function popup(popup_name)
    {
      get_popup=document.getElementById(popup_name);
      if(get_popup.style.display=="flex")
      {
        get_popup.style.display="none";
      }
      else
      {
        get_popup.style.display="flex";
      }
    }
  </script>

</body>
</html>
