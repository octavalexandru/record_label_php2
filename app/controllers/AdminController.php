<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Album.php';
require_once __DIR__ . '/../models/Merchandise.php';
require_once __DIR__ . '/../models/Analytics.php';

class AdminController extends Controller
{
    private static function requireAdmin()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    echo '<pre>';
    print_r($_SESSION);
    echo '</pre>';

    $role = isset($_SESSION['role']) ? trim($_SESSION['role']) : null;

    echo "<p>DEBUG: role = " . htmlspecialchars($role) . "</p>";
    echo "<p>DEBUG: role === 'admin' → " . var_export($role === 'admin', true) . "</p>";

    if ($role !== 'admin') {
        http_response_code(403);
        exit('<p style="color:red">Access denied. Admins only.</p>');
    }

    echo "<p style='color:green'>Access GRANTED</p>";
}



    public static function restock()
{
    self::requireAdmin();

    $albums = (new Album())->all();
    $merch  = (new Merchandise())->all();

    self::view('admin/restock', [
        'albums' => $albums,
        'merch'  => $merch,
        'success' => $_SESSION['restock_success'] ?? null
    ]);

    unset($_SESSION['restock_success']);
}


    public static function restockSave()
    {
        self::requireAdmin();
        self::checkCsrfToken();

        $type     = $_POST['type'] ?? '';
        $itemId   = (int)($_POST['item_id'] ?? 0);
        $quantity = (int)($_POST['quantity'] ?? 0);
        $price    = (float)($_POST['price'] ?? 0);

        if ($quantity < 0 || $price < 0) {
            return self::view('admin/restock', ['error' => 'Quantity and price must be non-negative.']);
        }

        if ($type === 'album') {
            (new Album())->updateStockAndPrice($itemId, $quantity, $price);
        } elseif ($type === 'merch') {
            (new Merchandise())->updateStockAndPrice($itemId, $quantity, $price);
        }

        $_SESSION['restock_success'] = 'Stock updated successfully.';
        self::redirect('/admin/restock');
    }

    public static function analytics()
    {
        self::requireAdmin();
        $analytics = new Analytics();
        $logs = $analytics->getRecent(100);

        self::view('admin/analytics', ['logs' => $logs]);
    }
}
