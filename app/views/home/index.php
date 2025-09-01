<h1>Welcome to the Record Label</h1>
<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<pre><?php print_r($_SESSION); ?></pre>

<nav>
    <ul>
        <li><a href="/artists/index">Artists</a></li>
        <li><a href="/albums/index">Albums</a></li>
        <li><a href="/merch/index">Merchandise</a></li>
        <li><a href="/cart/view">Cart</a></li>

        <?php if (!isset($_SESSION['user_id'])): ?>
            <li><a href="/auth/login">Login</a></li>
            <li><a href="/auth/register">Register</a></li>
        <?php else: ?>
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <li><a href="/admin/restock">Restock</a></li>
                <li><a href="/admin/analytics">Analytics</a></li>
            <?php endif; ?>
            <li>
                <form action="/auth/logout" method="POST" style="display:inline;">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                    <button type="submit">Logout</button>
                </form>
            </li>
        <?php endif; ?>
    </ul>

</nav>
