<?php

namespace App\Helpers;

class AppHelper
{
    public static function printOnScreen(mixed $str, bool $return = false): ?string
    {
        $print = "<pre>" . print_r($str, true) . "</pre>";
        if ($return) {
            return $print;
        }
        echo $print;
        return null;
    }
}
