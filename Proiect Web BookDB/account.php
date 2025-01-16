<?php

session_start();  

 
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    
    header("Location: login.php");
    exit;
}

 
$user_id = $_SESSION['user_id'];

require 'db.php';

 
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");  
    exit;
}

 
$username = $_SESSION['username'];
$sql = "SELECT id, username, email, passwords FROM users WHERE username = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 's', $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$user = mysqli_fetch_assoc($result);
 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['old_password'], $_POST['new_password'], $_POST['confirm_password'])) {
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
 
    if (password_verify($old_password, $user['passwords'])) {
        
        if ($new_password === $confirm_password) {
           
            $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);

            $update_sql = "UPDATE users SET passwords = ? WHERE id = ?";
            $update_stmt = mysqli_prepare($conn, $update_sql);
            mysqli_stmt_bind_param($update_stmt, 'si', $hashed_new_password, $user['id']);
            if (mysqli_stmt_execute($update_stmt)) {
                $message = "Password updated successfully!";
            } else {
                $error = "There was an error updating the password.";
            }
        } else {
            $error = "The new passwords do not match.";
        }
    } else {
        $error = "The old password is incorrect.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BookDB</title>
     
    <link rel="stylesheet" href="style/css.css">
    <link rel="stylesheet" href="style/account.css">
     
    <script src="script.js" defer></script>
</head>
<body>
<header>
    <div class="logo">
        <a href="index.php">BookDB</a>
    </div>
    <nav>
        <a href="mylist.php">Wish List</a>
        <a href="index.php">Home</a>
        <a href="account.php">Account</a>
    </nav>
</header>
    <div class="container">
        <h1>Here are your information account</h1>
        <?php if (isset($message)): ?>
            <p class="success"><?= $message; ?></p>
        <?php elseif (isset($error)): ?>
            <p class="error"><?= $error; ?></p>
        <?php endif; ?>

        <p><strong>Username:</strong> <?= htmlspecialchars($user['username']); ?></p>
        <p><strong>E-mail:</strong> <?= htmlspecialchars($user['email']); ?></p>

        <h2>Change Password</h2>
        <form method="post" action="account.php">
            <label for="old_password">Old Password:</label>
            <input type="password" name="old_password" id="old_password" required>

            <label for="new_password">New Password:</label>
            <input type="password" name="new_password" id="new_password" required>

            <label for="confirm_password">Confirm New Password:</label>
            <input type="password" name="confirm_password" id="confirm_password" required>

            <button type="submit">Change Password</button>
        </form>
        <br>

        <button><a href="logout.php?logout=true">Logout</a></button>
    </div>

    <footer>
        <p>© 2025 Books Store - All rights reserved. <a href="#">Privacy Policy</a> | <a href="#">Terms of Service</a></p>
    </footer>
</body>
