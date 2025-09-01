<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    echo '<p style="color:red;">Access denied. Admins only.</p>';
    return;
}
?>

<h1>Restock Inventory</h1>

<?php if (!empty($success)): ?>
  <p style="color:green;"><?php echo htmlspecialchars($success); ?></p>
<?php elseif (!empty($error)): ?>
  <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
<?php endif; ?>

<!-- Add New Item -->
<h2>Add New Item</h2>
<form method="post" action="/admin/update">
  <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">

  <label>Type:</label>
  <select name="type" required>
    <option value="album">Album</option>
    <option value="merch">Merchandise</option>
  </select><br>

  <label>Name:</label>
  <input type="text" name="name" required><br>

  <label>Price:</label>
  <input type="number" step="0.01" name="price" required><br>

  <label>Stock:</label>
  <input type="number" name="stock" required><br>

  <label>Artist (for albums only):</label>
  <select name="artist_id">
    <option value="">None</option>
    <?php foreach ($artists as $artist): ?>
      <option value="<?php echo $artist['artist_id']; ?>">
        <?php echo htmlspecialchars($artist['name']); ?>
      </option>
    <?php endforeach; ?>
  </select><br>

  <button type="submit" name="action" value="add">Add Item</button>
</form>

<hr>

<!-- Update Stock of Existing Items -->
<h2>Update Stock</h2>
<form method="post" action="/admin/update">
  <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">

  <label>Select Item:</label>
  <select name="item_id" required>
    <?php foreach ($albums as $album): ?>
      <option value="album-<?php echo $album['album_id']; ?>">
        Album: <?php echo htmlspecialchars($album['title']); ?>
      </option>
    <?php endforeach; ?>
    <?php foreach ($merch as $m): ?>
      <option value="merch-<?php echo $m['merchandise_id']; ?>">
        Merch: <?php echo htmlspecialchars($m['name']); ?>
      </option>
    <?php endforeach; ?>
  </select><br>

  <label>New Stock Quantity:</label>
  <input type="number" name="stock" required><br>

  <button type="submit" name="action" value="restock">Update Stock</button>
</form>
