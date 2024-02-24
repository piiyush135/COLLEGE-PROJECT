<?php

require('connection.php');
#for login
if(isset($_POST['login']))
{
   $query="SELECT * FROM 'registered_user' WHERE 'email'='$_post[email_username]' OR 'username'='$_post[email_username]'";
   $result=mysqli_query($con,$query);

   if($result)
   {
     if(mysqli_num_rows($result)==1)
     {
       $result_fetch=mysqli_fetch_assoc($result);
     }
     else
     {
        echo"
       <script>
       alert('Email or Username  Not Registered');
       window.location.href='index.php';
       </script>
       ";
     }
   }
   else
   {
    echo"
       <script>
       alert('cannot Run Query');
       window.location.href='index.php';
       </script>
       ";
   }
}


#for registration
if(isset($_post['register']))
{
    $user_exist_query="SELECT * FROM 'registered_user' WHERE 'Username'='$_post[username]'OR 'E-mail'='$_post'[email]";
    $result=mysqli_query($con,$user_exist_query);

    if($result)
    {
      if(mysqli_num_rows($result)>0)
      {
        $result_fetch=mysqli_fetch_assoc($result);
        if($result_fetch['username']==$_post['username'])
        {
            echo"
            <script> 
             alert('$result_fetch[username]-Username already taken');
              window.location.href='index.php';
            </script>
            ";
        }
      }
      else
      {
        echo"
            <script>
             alert('$result_fetch[email]-E-mail already registered');
              window.location.href='index.php';
            </script>
            ";
      }
    }
        else
        {
          $password=password_hash($_POST['password'],PASSWORD_BCRYPT);
          $query="INSERT INTO `registered_user`(`Full Name`, `Username`, `E-mail`, `Password`) VALUES ($_post'[fullname]','$_post[username]','$_post[email]','$password')";
          if(mysqli_query($con,$query))
          {
            echo"
            <script>
              alert('registration successful');
               window.location.href='index.php';
           </script>
         ";
          }
          else
          {
            echo"
             <script>
               alert('cannot Run Query');
                window.location.href='index.php';
            </script>
          ";
          }
        }
    }
    else
    {
       echo"
       <script>
       alert('cannot Run Query');
       window.location.href='index.php';
       </script>
       ";
    }
?>