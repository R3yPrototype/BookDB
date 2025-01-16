<?php

$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'movies';

$conn = mysqli_connect($host, $username, $password, $dbname);


if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}


function get_all_books() {
    global $conn;
    $sql = "SELECT * FROM books";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        die("Error executing query: " . mysqli_error($conn)); 
    }

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}


function get_book_by_id($book_id) {
    global $conn;
    $sql = "SELECT id, title, price, description, release_date, genre, author, rating, cover_image FROM books WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $book_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
 
    return mysqli_fetch_assoc($result);
}
