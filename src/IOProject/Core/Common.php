<?php namespace IOProject\Core;

abstract class Common {
    
    public static function changeCost($cost) {
        return round($cost, 2) . ' ' . Config::CURRENCY; // format 0000.00 CURRENCY
    }

    public static function calculatePercentage($amount, $percent) {
        return ($amount * $percent) / 100;
    }
}