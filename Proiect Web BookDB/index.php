<?php
session_start();


if (!isset($_SESSION['user_id'])) {
   
    header('Location: login.php'); 
    exit();
}

$user_id = $_SESSION['user_id'];


require 'db.php'; 


$genre_filter = isset($_GET['genre']) ? $_GET['genre'] : '';


$books = get_books_by_genre($genre_filter);


function get_books_by_genre($genre = '')
{
    global $conn;
    if ($genre) {
        $stmt = mysqli_prepare($conn, "SELECT * FROM books WHERE genre = ?");
        mysqli_stmt_bind_param($stmt, 's', $genre);
    } else {
        $stmt = mysqli_prepare($conn, "SELECT * FROM books");
    }
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}


function get_genres()
{
    global $conn;
    $sql = "SELECT DISTINCT genre FROM books";
    $result = mysqli_query($conn, $sql);
    $genres = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $genres[] = $row['genre'];
    }
    return $genres;
}

$genres = get_genres();
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

    <h2>Our Books</h2>

   
    <div class="filter-section">
        <form method="GET" action="index.php">
            <select name="genre">
                <option value="">All Genres</option>
                <?php foreach ($genres as $genre): ?>
                    <option value="<?= htmlspecialchars($genre); ?>" <?= $genre_filter == $genre ? 'selected' : ''; ?>>
                        <?= htmlspecialchars($genre); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit">Filter</button>
        </form>
    </div>

    <div class="book-list">
        <?php if (empty($books)): ?>
            <p style="text-align:center; font-size:1.2em; color: #888;">No books available at the moment. Please check back later!</p>
        <?php else: ?>
            <?php foreach ($books as $book): ?>
                <div class="book">
                    <a href="book.php?title=<?= urlencode($book['title']); ?>">
                        <h3><?= htmlspecialchars($book['title']); ?></h3>
                        <p class="price">$<?= number_format($book['price'], 2); ?></p>
                        <?php if (!empty($book['cover_image'])): ?>
                            <img src="<?= htmlspecialchars($book['cover_image']); ?>" alt="<?= htmlspecialchars($book['title']); ?>" />
                        <?php else: ?>
                            <p style="text-align: center; color: #aaa;">No image available</p>
                        <?php endif; ?>
                        <p><strong>Description:</strong> <?= htmlspecialchars($book['description']); ?></p>
                        <p><strong>Release Date:</strong> <?= htmlspecialchars($book['release_date']); ?></p>
                        <p><strong>Genre:</strong> <?= htmlspecialchars($book['genre']); ?></p>
                        <p><strong>Author:</strong> <?= htmlspecialchars($book['author']); ?></p>
                        <p><strong>Rating:</strong> <?= number_format($book['rating'], 1); ?></p>
                    </a>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <footer>
        <p>© 2025 Books Store - All rights reserved. <a href="#">Privacy Policy</a> | <a href="#">Terms of Service</a></p>
    </footer>

</body>

</html>