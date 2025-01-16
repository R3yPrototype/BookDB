<?php
session_start();
require 'db.php'; 

// Verifică dacă utilizatorul este logat
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header("Location: login.php"); // Redirecționează la login dacă nu este logat
    exit;
}

$user_id = $_SESSION['user_id']; // Preia ID-ul utilizatorului din sesiune

// Verifică dacă există un parametru 'title' în URL
if (isset($_GET['title'])) {
    $book_title = urldecode($_GET['title']); // Decodează titlul cărții

    // Funcție pentru a obține detaliile unei cărți după titlu
    function get_book_by_title($title) {
        global $conn;
        $stmt = mysqli_prepare($conn, "SELECT id, title, price, description, release_date, genre, author, rating, cover_image FROM books WHERE title = ?");
        mysqli_stmt_bind_param($stmt, 's', $title);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($result);
    }

    $book = get_book_by_title($book_title); // Obține detaliile cărții

    if (!$book) {
        die('Invalid book title');
    }

    // Verifică dacă cartea este deja în lista de dorințe a utilizatorului
    function is_book_in_wishlist($user_id, $book_id) {
        global $conn;
        $stmt = mysqli_prepare($conn, "SELECT * FROM wishlist WHERE user_id = ? AND book_id = ?");
        mysqli_stmt_bind_param($stmt, 'ii', $user_id, $book_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_num_rows($result) > 0;
    }

    $is_in_wishlist = is_book_in_wishlist($user_id, $book['id']); // Verifică dacă cartea este în wishlist
} else {
    die('Invalid book title');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($book['title']); ?>BookDB</title>
    <link rel="stylesheet" href="style/css.css">
    <link rel="stylesheet" href="style/book.css">
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
        <h1>Book Details: <?= htmlspecialchars($book['title']); ?></h1>
        <p><strong>Price:</strong> $<?= number_format($book['price'], 2); ?></p>
        <p><strong>Description:</strong> <?= htmlspecialchars($book['description']); ?></p>
        <p><strong>Release Date:</strong> <?= htmlspecialchars($book['release_date']); ?></p>
        <p><strong>Genre:</strong> <?= htmlspecialchars($book['genre']); ?></p>
        <p><strong>Author:</strong> <?= htmlspecialchars($book['author']); ?></p>
        <p><strong>Rating:</strong> <?= number_format($book['rating'], 1); ?></p>

        <?php if (!empty($book['cover_image'])): ?>
            <img src="<?= htmlspecialchars($book['cover_image']); ?>" alt="<?= htmlspecialchars($book['title']); ?>" />
        <?php else: ?>
            <p>No image available</p>
        <?php endif; ?>

        <button id="wishlist-button" onclick="toggleWishlist(<?= $book['id']; ?>)">
            <?= $is_in_wishlist ? 'Remove from Wishlist' : 'Add to My Wishlist'; ?>
        </button>
    </div>

    <footer>
        <p>© 2025 Books Store - All rights reserved. <a href="#">Privacy Policy</a> | <a href="#">Terms of Service</a></p>
    </footer>
    <script src="js.js"></script>
</body>
</html>
