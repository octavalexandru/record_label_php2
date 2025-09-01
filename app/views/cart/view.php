<?php require_once __DIR__ . '/../../services/CurrencyService.php'; ?>

<h1>Your Cart</h1>

<?php if (!empty($items)): ?>
  <ul>
    <?php foreach ($items as $item): ?>
      <li>
        <?php if (!empty($item['album_title'])): ?>
          Album: <?= htmlspecialchars($item['album_title']) ?>
        <?php elseif (!empty($item['merch_name'])): ?>
          Merch: <?= htmlspecialchars($item['merch_name']) ?>
        <?php endif; ?>

        - Price: $<?= htmlspecialchars($item['price']) ?>
        <?php
          $ron = CurrencyService::convertToRon($item['price']);
          if ($ron !== null):
        ?>
          (<?= $ron ?> RON)
        <?php else: ?>
          (RON unavailable)
        <?php endif; ?>

        - Quantity: <?= htmlspecialchars($item['quantity']) ?>

        <form method="post" action="/cart/update" style="display:inline;">
          <input type="hidden" name="cart_item_id" value="<?= htmlspecialchars($item['cart_item_id']) ?>">
          <?php if (!isset($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); ?>
          <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
          <input type="number" name="quantity" value="<?= htmlspecialchars($item['quantity']) ?>" min="1">
          <button type="submit">Update</button>
        </form>

        <form method="post" action="/cart/remove" style="display:inline;">
          <input type="hidden" name="cart_item_id" value="<?= htmlspecialchars($item['cart_item_id']) ?>">
          <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
          <button type="submit">Remove</button>
        </form>
      </li>
    <?php endforeach; ?>
  </ul>

  <form method="post" action="/cart/checkout">
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
    <button type="submit">Checkout</button>
  </form>

<?php else: ?>
  <p>Your cart is empty.</p>
<?php endif; ?>
