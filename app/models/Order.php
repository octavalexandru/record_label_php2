<?php
require_once __DIR__ . '/../core/Model.php';

class Order extends Model {
    public function __construct() {
        parent::__construct("orders", "order_id");
    }
}
