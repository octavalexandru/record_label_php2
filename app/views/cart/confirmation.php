<h2>Order Confirmed!</h2>

<p>Order ID:
    <?= isset($cart['cart_id']) ? htmlspecialchars($cart['cart_id'], ENT_QUOTES, 'UTF-8') : 'Unknown' ?>
</p>

<?php if (!empty($items) && is_array($items)): ?>
    <ul>
        <?php foreach ($items as $item): ?>
            <li>
                <?= htmlspecialchars($item['name'] ?? 'Unknown item', ENT_QUOTES, 'UTF-8') ?>
                × <?= (int)($item['quantity'] ?? 1) ?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>No items found in your order.</p>
<?php endif; ?>

<p>Thank you for shopping with us!</p>
<a href="/">Back to Home</a>
