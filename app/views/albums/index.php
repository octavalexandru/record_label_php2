<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<h1>Albums</h1>

<?php if (!empty($albums)): ?>
    <ul>
        <?php foreach ($albums as $album): ?>
            <li>
                <strong><?php echo htmlspecialchars($album['title'] ?? 'Untitled'); ?></strong> by
                <?php echo htmlspecialchars($album['artist_name'] ?? 'Unknown Artist'); ?>
                - $<?php echo htmlspecialchars($album['price'] ?? '0.00'); ?>

                <form method="post" action="/cart/add" style="display:inline;">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <input type="hidden" name="type" value="album">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($album['album_id']); ?>">
                    <input type="hidden" name="price" value="<?php echo htmlspecialchars($album['price']); ?>">
                    <input type="number" name="quantity" value="1" min="1" style="width: 50px;">
                    <button type="submit">Add to Cart</button>
                </form>

                <a href="/albums/show?id=<?php echo urlencode($album['album_id']); ?>">View</a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>No albums found.</p>
<?php endif; ?>
