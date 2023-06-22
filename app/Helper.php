<?php
namespace App;

class Helper
{
    public static function createFrontAnswer(string $text, mixed $data = null):array {
//        return json_encode(['status' => $text, 'data' => $data], JSON_UNESCAPED_UNICODE);
        return ['status' => $text, 'data' => $data];
    }
}
