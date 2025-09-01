<h1><?php echo htmlspecialchars($item['name']); ?></h1>
<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>

<p>Type: <?php echo htmlspecialchars($item['type']); ?></p>
<p>Description: <?php echo htmlspecialchars(nl2br(htmlspecialchars($item['description']))); ?></p>
<p>Price: $<?php echo htmlspecialchars($item['price']); ?></p>
<p>Stock: <?php echo htmlspecialchars((int)$item['stock']); ?></p>

<form method="post" action="/cart/add">
  <input type="hidden" name="type" value="merch">
<?php if (!isset($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); ?>
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
  <input type="hidden" name="id" value="<?php echo htmlspecialchars($item['merchandise_id']); ?>">
  <input type="hidden" name="price" value="<?php echo htmlspecialchars($item['price']); ?>">

  <label>Quantity:
    <input type="number" name="quantity" value="1" min="1" max="<?php echo htmlspecialchars($item['stock']); ?>">
  </label>

  <?php if (stripos($item['name'], 'shirt') !== false || stripos($item['name'], 't-shirt') !== false): ?>
    <label>Size:
      <select name="size">
        <option value="S">S</option>
        <option value="M" selected>M</option>
        <option value="L">L</option>
        <option value="XL">XL</option>
      </select>
    </label>
  <?php endif; ?>

  <button type="submit">Add to Cart</button>
</form>
