<?php
session_start();
require 'db.php'; 

// Verifică dacă utilizatorul este logat
if (!isset($_SESSION['logged_in']) 
    || !$_SESSION['logged_in']) {
    header("Location: login.php"); 
    exit;
}

// Preia ID-ul utilizatorului din sesiune
$user_id = $_SESSION['user_id']; 

// Funcție pentru a obține cărțile din wishlist
function get_books_from_wishlist($user_id) {
    global $conn;
    $stmt = mysqli_prepare($conn, "SELECT b.id, b.title, b.price, b.description, b.release_date, b.genre, b.author, b.rating, b.cover_image 
                                    FROM wishlist w
                                    JOIN books b ON w.book_id = b.id
                                    WHERE w.user_id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $books = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return $books;
}

$books = get_books_from_wishlist($user_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BookDB</title>
    <link rel="stylesheet" href="style/css.css">
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
    <h1>My Wishlist</h1>
    
    <?php if (empty($books)): ?>
        <p>Your wishlist is empty. Start adding books!</p>
    <?php else: ?>
        <div class="book-list">
            <?php foreach ($books as $book): ?>
                <div class="book">
                    <a href="book.php?title=<?= urlencode($book['title']); ?>">
                        <h3><?= htmlspecialchars($book['title']); ?></h3>
                        <p class="price">$<?= number_format($book['price'], 2); ?></p>
                        <?php if (!empty($book['cover_image'])): ?>
                            <img src="<?= htmlspecialchars($book['cover_image']); ?>" alt="<?= htmlspecialchars($book['title']); ?>" />
                        <?php else: ?>
                            <p>No image available</p>
                        <?php endif; ?>
                        <p><strong>Description:</strong> <?= htmlspecialchars($book['description']); ?></p>
                        <p><strong>Release Date:</strong> <?= htmlspecialchars($book['release_date']); ?></p>
                        <p><strong>Genre:</strong> <?= htmlspecialchars($book['genre']); ?></p>
                        <p><strong>Author:</strong> <?= htmlspecialchars($book['author']); ?></p>
                        <p><strong>Rating:</strong> <?= number_format($book['rating'], 1); ?></p>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <footer>
        <p>© 2025 Books Store - All rights reserved. <a href="#">Privacy Policy</a> | <a href="#">Terms of Service</a></p>
    </footer>
</body>
</html>


