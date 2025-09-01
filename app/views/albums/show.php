<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<?php if (!empty($album)): ?>
    <h1><?php echo htmlspecialchars($album['title']); ?></h1>
    <p><strong>Artist:</strong> <?php echo htmlspecialchars($album['artist_name']); ?></p>
    <p><strong>Price:</strong> $<?php echo htmlspecialchars($album['price']); ?></p>
    <p><strong>Release Date:</strong> <?php echo htmlspecialchars($album['release_date']); ?></p>

    <form method="post" action="/cart/add">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <input type="hidden" name="type" value="album">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($album['album_id']); ?>">
        <input type="hidden" name="price" value="<?php echo htmlspecialchars($album['price']); ?>">
        <label>
            Quantity:
            <input type="number" name="quantity" value="1" min="1" style="width: 50px;">
        </label>
        <button type="submit">Add to Cart</button>
    </form>

    <p><a href="/albums">Back to Albums</a></p>
<?php else: ?>
    <p>Album not found.</p>
<?php endif; ?>
