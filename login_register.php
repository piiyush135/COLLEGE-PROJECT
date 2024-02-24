<?php

require('connection.php');

// For login
if(isset($_POST['login']))
{
   $email_username = $_POST['email_username'];
   $query = "SELECT * FROM registered_user WHERE email='$email_username' OR username='$email_username'";
   $result = mysqli_query($con, $query);

   if($result)
   {
     if(mysqli_num_rows($result) == 1)
     {
       $result_fetch = mysqli_fetch_assoc($result);
       // Perform further authentication checks here
     }
     else
     {
        echo "
       <script>
       alert('Email or Username Not Registered');
       window.location.href='index.php';
       </script>
       ";
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
   header("location:main.html");
   exit();
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
