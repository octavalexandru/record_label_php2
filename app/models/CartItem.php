<?php
require_once __DIR__ . '/../core/Model.php';

class CartItem extends Model {
    public function __construct() {
        parent::__construct("cartitems", "cart_item_id");
    }

    public function getByCart($cart_id) {
        $sql = "SELECT * FROM cartitems WHERE cart_id = ?";
        return $this->query($sql, [$cart_id]);
    }

    public function removeItem($cart_item_id) {
        $sql = "DELETE FROM cartitems WHERE cart_item_id = ?";
        return $this->execute($sql, [$cart_item_id]);
    }

    public function updateQuantity($cart_item_id, $quantity) {
        $sql = "UPDATE cartitems SET quantity = ? WHERE cart_item_id = ?";
        return $this->execute($sql, [$quantity, $cart_item_id]);
    }
}
