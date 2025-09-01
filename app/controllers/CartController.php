<?php
// app/controllers/CartController.php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Cart.php';
require_once __DIR__ . '/../models/CartItem.php';
require_once __DIR__ . '/../models/Album.php';
require_once __DIR__ . '/../models/Merchandise.php';
require_once __DIR__ . '/../core/MailService.php';

class CartController extends Controller {
    public static function index() {
        session_start();
        $user_id = $_SESSION['user_id'] ?? null;
        $session_token = $_SESSION['session_token'] ?? session_id();

        $cartModel = new Cart();
        $cart = $cartModel->getActiveCart($user_id, $session_token);

        if (!$cart) {
            return self::view("cart/view", ["items" => []]);
        }

        $items = $cartModel->getItems($cart['cart_id']);
        self::view("cart/view", ["items" => $items]);
    }

    public static function add() {
        self::checkCsrfToken(); 
        session_start();
        if (!isset($_POST['type'], $_POST['id'], $_POST['price'])) {
            return self::redirect("/cart/view");
        }

        $type = $_POST['type'];
        $item_id = (int)$_POST['id'];
        $price = (float)$_POST['price'];
        $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

        $user_id = $_SESSION['user_id'] ?? null;
        $session_token = $_SESSION['session_token'] ?? session_id();

        $cartModel = new Cart();
        $cart = $cartModel->getActiveCart($user_id, $session_token);

        if (!$cart) {
            $data = ['is_active' => 1, 'created_at' => date("Y-m-d H:i:s")];
            if ($user_id) $data['user_id'] = $user_id;
            else $data['session_token'] = $session_token;

            $cart_id = $cartModel->insert($data);
        } else {
            $cart_id = $cart['cart_id'];
        }

        $cartModel->addItem($cart_id, $type, $item_id, $price, $quantity);
        self::redirect("/cart/view");
    }

    public static function update() {
        self::checkCsrfToken(); 
        if (!isset($_POST['cart_item_id'], $_POST['quantity'])) {
            return self::redirect("/cart/view");
        }

        $itemModel = new CartItem();
        $itemModel->updateQuantity((int)$_POST['cart_item_id'], (int)$_POST['quantity']);

        self::redirect("/cart/view");
    }

    public static function remove() {
        self::checkCsrfToken(); 
        if (!isset($_POST['cart_item_id'])) {
            return self::redirect("/cart/view");
        }

        $itemModel = new CartItem();
        $itemModel->removeItem((int)$_POST['cart_item_id']);

        self::redirect("/cart/view");
    }

    public static function checkout() {
        self::checkCsrfToken();
        session_start();

        $user_id = $_SESSION['user_id'] ?? null;
        $session_token = $_SESSION['session_token'] ?? session_id();

        $cartModel = new Cart();
        $cart = $cartModel->getActiveCart($user_id, $session_token);

        if (!$cart) {
            return self::view("cart/view", ["items" => [], "error" => "Cart is empty."]);
        }

        $items = $cartModel->getItems($cart['cart_id']);
        $errors = [];

        foreach ($items as $item) {
            $ok = false;

            if ($item['album_id']) {
                $model = new Album();
                $ok = $model->decreaseStock($item['album_id'], $item['quantity']);
            } elseif ($item['merchandise_id']) {
                $model = new Merchandise();
                $ok = $model->decreaseStock($item['merchandise_id'], $item['quantity']);
            }

            if (!$ok) {
                $errors[] = "Insufficient stock for item ID " . ($item['album_id'] ?: $item['merchandise_id']);
            }
        }

        if (!empty($errors)) {
            return self::view("cart/view", ["items" => $items, "error" => implode("<br>", $errors)]);
        }

        $cartModel->markAsCheckedOut($cart['cart_id']);

        // (Optional) Email confirmation
        if ($user_id && isset($_SESSION['username'])) {
            MailService::sendOrderConfirmation($_SESSION['username'], $user_id);
        }

        self::view("cart/confirmation", []);
        $analytics = new Analytics();
        $analytics->log('cart_add', "/cart/add", $_SESSION['user_id'] ?? null);
    }
}
