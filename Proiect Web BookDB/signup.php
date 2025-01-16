<?php
require 'db.php'; // Include the database connection

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Hash the password for security
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the query to insert the user
    $sql = "INSERT INTO users (username, email, passwords) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "sss", $username, $email, $hashedPassword);
        $result = mysqli_stmt_execute($stmt);

        if ($result) {
            // Automatically log in the user after successful sign-up
            session_start();
            $_SESSION['logged_in'] = true;
            $_SESSION['username'] = $username;
            header("Location: index.php"); // Redirect to the homepage
            exit;
        } else {
            $error = "Error creating account: " . mysqli_error($conn);
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
     <h3 style="color:aliceblue"> Welcome to the sign Up page!</h3>
    </header>

    <div class="container">
        <h1>Sign Up</h1>
        <?php if (isset($error)): ?>
            <p class="error"><?= htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <form method="post" action="signup.php">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" required>

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>

            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>

            <button type="submit">Sign Up</button>
        </form>

        <p>Already have an account? <a href="login.php">Log in here</a></p>
    </div>

    <footer>
        <p>© 2025 Books Store - All rights reserved. <a href="#">Privacy Policy</a> | <a href="#">Terms of Service</a></p>
    </footer>
</body>
</html>
