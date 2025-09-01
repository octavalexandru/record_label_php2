<?php

class Controller {

    protected static function view($path, $data = []) {

        extract($data);

        require __DIR__ . "/../views/{$path}.php";

    }

    public static function ensureSessionStarted() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
    }


    protected static function redirect($url) {

        header("Location: {$url}");

        exit;

    }

    protected static function checkCsrfToken() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }    
        $token = $_POST['csrf_token'] ?? '';
        if (empty($token) || !isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
            http_response_code(403);
            exit('Forbidden: Invalid CSRF token');
        }
    }




}