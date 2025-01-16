<?php
session_start();  
require 'db.php'; 

 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

  
    $sql = "SELECT id, passwords FROM users WHERE username = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
         
            if (password_verify($password, $row['passwords'])) {
                
                $_SESSION['logged_in'] = true;
                $_SESSION['user_id'] = $row['id'];  
                $_SESSION['username'] = $username;  
 
                header("Location: index.php");
                exit;    
            } else {
                $error = "Invalid username or password.";
            }
        } else {
            $error = "Invalid username or password.";
        }
        mysqli_stmt_close($stmt);
    } else {
        $error = "Database error: " . mysqli_error($conn);
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
</head>
<body>
    <header>
        <h3 style="color:aliceblue">Welcome to the Log In page!</h3>
    </header>

    <div class="container">
        <h1>Login</h1>
        <?php if (isset($error)): ?>
            <p class="error"><?= htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <form method="post" action="login.php">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" required>

            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>

            <button type="submit">Login</button>
        </form>

        <p>Don't have an account? <a href="signup.php">Sign up here</a></p>
    </div>
<br>

    <footer>
        <p>© 2025 Books Store - All rights reserved. <a href="#">Privacy Policy</a> | <a href="#">Terms of Service</a></p>
    </footer>
</body>
</html>


