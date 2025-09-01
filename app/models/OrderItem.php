<?php
require_once __DIR__ . '/../core/Model.php';

class OrderItem extends Model {
    public function __construct() {
        parent::__construct("orderitems", "order_item_id");
    }
}
