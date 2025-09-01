<?php
require_once __DIR__ . "/../core/Controller.php";
require_once __DIR__ . "/../models/Order.php";
require_once __DIR__ . "/../models/OrderItem.php";

class OrderController extends Controller {
    public static function history() {
        self::view("orders/history");
    }

    public static function show() {
        self::view("orders/show");
    }

    public static function checkout() {
        // process order (later)
    }
}
