<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Album.php';
require_once __DIR__ . '/../models/Merchandise.php';

class RestockController extends Controller {

    private static function isAdmin(): bool {
        return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
    }

    public static function index() {
        session_start();
        if (!self::isAdmin()) {
            http_response_code(403);
            echo "Access denied";
            exit;
        }

        $albums = (new Album())->all();
        $merch  = (new Merchandise())->all();

        self::view("admin/restock", ["albums" => $albums, "merch" => $merch]);
    }

    public static function update() {
        self::checkCsrfToken();
        session_start();
        if (!self::isAdmin()) {
            http_response_code(403);
            echo "Access denied";
            exit;
        }

        $itemType = $_POST['item_type'] ?? '';
        $itemId = $_POST['item_id'] ?? '';
        $newStock = (int) ($_POST['new_stock'] ?? 0);

        if ($itemType === 'album') {
            (new Album())->updateStock($itemId, $newStock);
        } elseif ($itemType === 'merch') {
            (new Merchandise())->updateStock($itemId, $newStock);
        }

        self::redirect('/admin/restock');
    }
}
