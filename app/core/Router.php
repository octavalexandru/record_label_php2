<?php

$routes = [

    // Artist routes
    "artists/index" => ["ArtistController", "index"],
    "artists/show"  => ["ArtistController", "show"],

    // Album routes
    "albums/index" => ["AlbumController", "index"],
    "albums/show"  => ["AlbumController", "show"],

    // Merchandise routes
    "merch/index"  => ["MerchController", "index"],
    "merch/show"   => ["MerchController", "show"],

    // Cart routes
    "cart/view"    => ["CartController", "index"],
    "cart/add"     => ["CartController", "add"],
    "cart/update"  => ["CartController", "update"],
    "cart/remove"  => ["CartController", "remove"],
    "cart/checkout" => ["CartController", "checkout"],

    // Auth routes
    "auth/login"    => ["AuthController", "login"],
    "auth/logout"   => ["AuthController", "logout"],
    "auth/register" => ["AuthController", "register"],
    "auth/verify"   => ["AuthController", "verify"],

    // Admin routes
    "admin/restock" => ["RestockController", "index"],
    "admin/update"  => ["RestockController", "update"],
    "admin/analytics" => ["AdminController", "analytics"],
    "admin/restock"       => ["AdminController", "restock"],
    "admin/restock/save"  => ["AdminController", "restockSave"],
    "admin/analytics"     => ["AdminController", "analytics"],



    // Home
    ""             => ["HomeController", "index"],
    "home"         => ["HomeController", "index"]
];

class Router {
    private $uri;

    public function __construct() {
        $this->uri = trim(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH), "/");
    }

    public function direct() {
        global $routes;

        if (array_key_exists($this->uri, $routes)) {
            [$controller, $method] = $routes[$this->uri];

            require_once __DIR__ . "/../controllers/{$controller}.php";
            return $controller::$method();
        }

        require_once __DIR__ . "/../views/404.php";
    }
}
    