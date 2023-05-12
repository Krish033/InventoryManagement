<?php


namespace App\Services;

class GeneralService {

    /**
     * Limit Decimals
     * @param mixed $int
     * @param mixed $seprator
     * @param mixed $count
     * @return float
     */
    public static function decimals(float $int, string $seprator = '.', int $count = 2): float {
        // split on seprator
        $string = str($int);
        $splitted = explode('.', $string);
        $decimals = isset($splitted[1]) ? $splitted[1] : 00;
        // substring the second item
        $decimals = substr($decimals, 0, 2);
        // join the item with the seprator
        return floatval(implode('.', [$splitted[0], $decimals]));
    }
}