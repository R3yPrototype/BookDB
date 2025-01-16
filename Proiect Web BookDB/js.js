function toggleWishlist(bookId) {
    const button = document.getElementById('wishlist-button');

    fetch('wishlist_action.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `book_id=${bookId}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            button.textContent = data.action === 'added' ? 'Remove from Wishlist' : 'Add to My Wishlist';
        } else {
            alert('Something went wrong!');
        }
    });
}