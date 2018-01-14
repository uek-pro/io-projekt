<?php namespace IOProject\Core;

abstract class Common {
    
    public static function changeCost($cost) {
        return round($cost, 2) . ' ' . Config::Currency; // format 0000.00 Currency
    }

    public static function calculatePercentage($amount, $percent) {
        return ($amount * $percent) / 100;
    }
}