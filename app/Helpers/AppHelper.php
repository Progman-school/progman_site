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

    public static function removeHtmlTagsFromString(string $string): string
    {
        return strip_tags(str_replace(
            ["<br />", "<br/>", "<br>"],
            "\n",
            $string
        ));
    }
}
