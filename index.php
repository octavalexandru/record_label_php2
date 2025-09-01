<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
error_log("Session started: " . json_encode($_SESSION));

require_once __DIR__ . '/vendor/autoload.php';




ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


Dotenv\Dotenv::createImmutable(__DIR__)->safeLoad();

$config = require __DIR__ . '/config/config.php';

date_default_timezone_set($config['app']['timezone']);
ini_set('display_errors', $config['app']['debug'] ? '1' : '0');
error_reporting($config['app']['debug'] ? E_ALL : (E_ALL & ~E_NOTICE));

require_once __DIR__ . '/app/core/Database.php';
require_once __DIR__ . '/app/core/Router.php';
$router = new Router();
$router->direct();
