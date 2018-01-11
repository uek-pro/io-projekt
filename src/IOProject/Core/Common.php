<?php namespace IOProject\Core;

abstract class Common {
    
    public static function changeCost($cost) {
        return round($cost, 2) . ' ' . Config::Currency;
        // TODO: format 0000.00 Currency
    }
}