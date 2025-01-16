<?php
session_start();
require 'db.php'; 

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    echo json_encode(['success' => false, 'message' => 'You must be logged in']);
    exit;
}

$user_id = $_SESSION['user_id']; 

if (isset($_POST['book_id'])) {
    $book_id = $_POST['book_id']; 

    function is_book_in_wishlist($user_id, $book_id) {
        global $conn;
        $stmt = mysqli_prepare($conn, "SELECT * FROM wishlist WHERE user_id = ? AND book_id = ?");
        mysqli_stmt_bind_param($stmt, 'ii', $user_id, $book_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_num_rows($result) > 0;
    }

   
    if (is_book_in_wishlist($user_id, $book_id)) {
         
        $stmt = mysqli_prepare($conn, "DELETE FROM wishlist WHERE user_id = ? AND book_id = ?");
        mysqli_stmt_bind_param($stmt, 'ii', $user_id, $book_id);
        mysqli_stmt_execute($stmt);
        echo json_encode(['success' => true, 'action' => 'removed']);
    } else {
        
        $stmt = mysqli_prepare($conn, "INSERT INTO wishlist (user_id, book_id) VALUES (?, ?)");
        mysqli_stmt_bind_param($stmt, 'ii', $user_id, $book_id);
        mysqli_stmt_execute($stmt);
        echo json_encode(['success' => true, 'action' => 'added']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid book ID']);
}
?>


