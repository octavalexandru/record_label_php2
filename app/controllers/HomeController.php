<?php
require_once __DIR__ . "/../core/Controller.php";
require_once __DIR__ . '/../controllers/AnalyticsController.php';
AnalyticsController::track();

class HomeController extends Controller {
    public static function index() {
        self::view("home/index");
    }
}
