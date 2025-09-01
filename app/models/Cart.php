
<?php
// app/models/Cart.php

require_once __DIR__ . '/../core/Model.php';

class Cart extends Model {
    public function __construct() {
        parent::__construct("cart", "cart_id");
    }

    public function getActiveCart($user_id = null, $session_token = null) {
        if ($user_id) {
            $sql = "SELECT * FROM cart WHERE user_id = ? AND is_active = 1 LIMIT 1";
            return $this->queryOne($sql, [$user_id]);
        } elseif ($session_token) {
            $sql = "SELECT * FROM cart WHERE session_token = ? AND is_active = 1 LIMIT 1";
            return $this->queryOne($sql, [$session_token]);
        }
        return null;
    }

    public function getItems($cart_id) {
        $sql = "
            SELECT ci.*, 
                   a.title AS album_title, a.stock AS album_stock, 
                   m.name AS merch_name, m.stock AS merch_stock
            FROM cartitems ci
            LEFT JOIN albums a ON ci.album_id = a.album_id
            LEFT JOIN merchandise m ON ci.merchandise_id = m.merchandise_id
            WHERE ci.cart_id = ?
        ";
        return $this->query($sql, [$cart_id]);
    }

    public function addItem($cart_id, $type, $item_id, $price, $quantity) {
        if ($type === 'album') {
            $sql = "INSERT INTO cartitems (cart_id, album_id, price, quantity) VALUES (?, ?, ?, ?)";
            $this->execute($sql, [$cart_id, $item_id, $price, $quantity]);
        } elseif ($type === 'merch') {
            $sql = "INSERT INTO cartitems (cart_id, merchandise_id, price, quantity) VALUES (?, ?, ?, ?)";
            $this->execute($sql, [$cart_id, $item_id, $price, $quantity]);
        }
    }

    public function markAsCheckedOut($cart_id) {
        return $this->update($cart_id, ['is_active' => 0]);
    }
}
