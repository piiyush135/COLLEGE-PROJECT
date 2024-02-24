<?php

require('connection.php');

// For login
if(isset($_POST['login'])) {
    $email_username = $_POST['email_username'];
    
    // Prepare the SQL query to prevent SQL injection
    $query = "SELECT * FROM registered_user WHERE email=? OR username=?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "ss", $email_username, $email_username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if($result) {
        if(mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);
            // Verify password and perform further authentication checks here
        } else {
            showError("Email or Username Not Registered");
        }
    } else {
        showError("Cannot Run Query");
    }
}

// For registration
if(isset($_POST['register'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    
    // Check if username or email already exists
    $user_exist_query = "SELECT * FROM registered_user WHERE Username=? OR E-mail=?";
    $stmt = mysqli_prepare($con, $user_exist_query);
    mysqli_stmt_bind_param($stmt, "ss", $username, $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if($result) {
        if(mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            if($user['Username'] == $username) {
                showError("$username - Username already taken");
            } elseif ($user['E-mail'] == $email) {
                showError("$email - E-mail already registered");
            }
        } else {
            // Insert new user
            $query = "INSERT INTO registered_user (Full Name, Username, E-mail, Password) VALUES (?, ?, ?, ?)";
            $stmt = mysqli_prepare($con, $query);
            mysqli_stmt_bind_param($stmt, "ssss", $_POST['fullname'], $username, $email, $password);
            if(mysqli_stmt_execute($stmt)) {
                showSuccess("Registration successful");
            } else {
                showError("Cannot Run Query");
            }
        }
    } else {
        showError("Cannot Run Query");
    }
}

function showError($message) {
    echo "<script>alert('$message'); window.location.href='index.php';</script>";
    exit();
}

function showSuccess($message) {
    echo "<script>alert('$message'); window.location.href='index.php';</script>";
    exit();
}

?>
