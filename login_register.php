<?php

require('connection.php');

// For login
if(isset($_POST['login'])) {
   $email_username = $_POST['email_username'];
   // Using prepared statement to prevent SQL injection
   $query = "SELECT * FROM registered_user WHERE email=? OR username=?";
   $stmt = mysqli_prepare($con, $query);
   mysqli_stmt_bind_param($stmt, "ss", $email_username, $email_username);
   mysqli_stmt_execute($stmt);
   $result = mysqli_stmt_get_result($stmt);

   if($result) {
     if(mysqli_num_rows($result) == 1) {
       $result_fetch = mysqli_fetch_assoc($result);
       // Perform further authentication checks here
       // Assuming authentication is successful, redirect to main.html
       header("Location: main.html");
       exit(); // Ensure that no further code is executed after redirection
     } else {
       showErrorAlert("Email or Username Not Registered");
     }
   } else {
      showErrorAlert("Cannot Run Query");
   }
}

// For registration
if(isset($_POST['register']))
{
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    
    $user_exist_query = "SELECT * FROM registered_user WHERE Username='$username' OR `E-mail`='$email'";
    $result = mysqli_query($con, $user_exist_query);

    if($result)
    {
      if(mysqli_num_rows($result) > 0)
      {
        $result_fetch = mysqli_fetch_assoc($result);
        if($result_fetch['Username'] == $username)
        {
            echo "
            <script> 
             alert('$username - Username already taken');
              window.location.href='index.php';
            </script>
            ";
        }
        elseif ($result_fetch['E-mail'] == $email)
        {
            echo "
            <script>
             alert('$email - E-mail already registered');
              window.location.href='index.php';
            </script>
            ";
        }
      }
      else
      {
          $query = "INSERT INTO registered_user (`Full Name`, `Username`, `E-mail`, `Password`) VALUES ('$_POST[fullname]', '$username', '$email', '$password')";
          if(mysqli_query($con, $query))
          {
            echo "
            <script>
              alert('Registration successful');
               window.location.href='index.php';
           </script>
         ";
          }
          else
          {
            echo "
             <script>
               alert('Cannot Run Query');
                window.location.href='index.php';
            </script>
          ";
          }
      }
    }
    else
    {
       echo "
       <script>
       alert('Cannot Run Query');
       window.location.href='index.php';
       </script>
       ";
    }
}
?>
