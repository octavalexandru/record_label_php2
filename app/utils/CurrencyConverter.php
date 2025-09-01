<?php
class CurrencyConverter {
    public static function convertToRON(float $amount, string $from = 'EUR'): ?float {
        $data = @file_get_contents('https://api.exchangerate.host/latest?base=' . $from . '&symbols=RON');
        if (!$data) return null;
        $json = json_decode($data, true);
        return isset($json['rates']['RON']) ? round($amount * $json['rates']['RON'], 2) : null;
    }
}
