<?php
// Minimal inline config — expects vlucas/phpdotenv already loaded in index.php
return [
    'app' => [
        'env'       => $_ENV['APP_ENV']       ?? 'local',
        'debug'     => filter_var($_ENV['APP_DEBUG'] ?? false, FILTER_VALIDATE_BOOL),
        'url'       => rtrim($_ENV['APP_URL'] ?? 'http://localhost', '/'),
        'timezone'  => $_ENV['APP_TIMEZONE']  ?? 'UTC',
    ],
    'db' => [
        'driver'    => $_ENV['DB_DRIVER']     ?? 'mysql',
        'host'      => $_ENV['DB_HOST']       ?? '127.0.0.1',
        'port'      => (int)($_ENV['DB_PORT'] ?? 3306),
        'database'  => $_ENV['DB_DATABASE']   ?? '',
        'username'  => $_ENV['DB_USERNAME']   ?? '',
        'password'  => $_ENV['DB_PASSWORD']   ?? '',
        'charset'   => $_ENV['DB_CHARSET']    ?? 'utf8mb4',
        'collation' => $_ENV['DB_COLLATION']  ?? 'utf8mb4_general_ci',
    ],
    'mail' => [
        'host'       => $_ENV['MAIL_HOST']       ?? 'smtp.example.com',
        'port'       => (int)($_ENV['MAIL_PORT'] ?? 587),
        'username'   => $_ENV['MAIL_USERNAME']   ?? '',
        'password'   => $_ENV['MAIL_PASSWORD']   ?? '',
        'from_email' => $_ENV['MAIL_FROM_EMAIL'] ?? 'noreply@example.com',
        'from_name'  => $_ENV['MAIL_FROM_NAME']  ?? 'Your App',
        'encryption' => $_ENV['MAIL_ENCRYPTION'] ?? 'tls', // or ssl
    ],
    'captcha' => [
        'site_key'   => $_ENV['RECAPTCHA_SITE_KEY']   ?? '',
        'secret_key' => $_ENV['RECAPTCHA_SECRET_KEY'] ?? '',
    ],
];
