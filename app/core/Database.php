<?php
// Minimal PDO singleton function
if (!function_exists('db')) {
    function db(array $cfg): PDO
    {
        static $pdo = null;
        if ($pdo instanceof PDO) return $pdo;

        $dsn = sprintf(
            '%s:host=%s;port=%d;dbname=%s;charset=%s',
            $cfg['driver'],
            $cfg['host'],
            $cfg['port'],
            $cfg['database'],
            $cfg['charset']
        );

        $user = $cfg['username'];
        $pass = $cfg['password'];

        $pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,   // 🔒 REAL prepared statements
            PDO::ATTR_STRINGIFY_FETCHES  => false,
        ]);

        // Optional: ensure proper charset/collation
        $pdo->exec("SET NAMES {$cfg['charset']} COLLATE {$cfg['collation']}");

        return $pdo;
    }
}
