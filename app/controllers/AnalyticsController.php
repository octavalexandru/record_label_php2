<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Analytics.php';

class AnalyticsController extends Controller {
    public static function track() {
        $analytics = new Analytics();
        $analytics->logVisit($_SERVER['REQUEST_URI'], $_SESSION['user_id'] ?? null);
    }
}
