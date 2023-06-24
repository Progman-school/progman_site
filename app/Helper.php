<?php
namespace App;

class Helper
{
    public static function createFrontAnswer(string $text, mixed $data = null):array {
        return ['status' => $text, 'data' => $data];
    }
}
