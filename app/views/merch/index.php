<h1>Merchandise</h1>
<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>

<ul>
<?php foreach ($items as $item): ?>
    <li>
    <a href="/merch/show?id=<?php echo htmlspecialchars($item['merchandise_id']); ?>">
      <?php echo htmlspecialchars($item['name']); ?>
    </a>
    <form method="post" action="/cart/add" style="display:inline;">
      <input type="hidden" name="type" value="merch">
<?php if (!isset($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); ?>
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
      <input type="hidden" name="id" value="<?php echo htmlspecialchars($item['merchandise_id']); ?>">
      <input type="hidden" name="price" value="<?php echo htmlspecialchars($item['price']); ?>">
      <button type="submit">Add to Cart</button>
    </form>
  </li>
<?php endforeach; ?>
</ul>
