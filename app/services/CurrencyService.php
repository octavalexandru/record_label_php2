<?php

class CurrencyService
{
    public static function convertToRon($amount, $base = 'EUR')
    {
        $url = "https://api.exchangerate.host/latest?base=" . urlencode($base) . "&symbols=RON";
        $response = @file_get_contents($url);

        if ($response === false) return null;

        $data = json_decode($response, true);
        if (!isset($data['rates']['RON'])) return null;

        return round($amount * $data['rates']['RON'], 2);
    }
}
