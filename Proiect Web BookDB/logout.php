<?php
session_start();
session_unset();
session_destroy();
header("Location: login.php");
exit;
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
<footer>
        <p>© 2025 Books Store - All rights reserved. <a href="#">Privacy Policy</a> | <a href="#">Terms of Service</a></p>
    </footer>
</body>
</html>
