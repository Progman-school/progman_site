<?php
namespace App\Helpers;

class ApiHelper
{
    public const OK_STATUS = "OK";
    public const ERROR_STATUS = 'error';
    public const DEFAULT_ERROR_TEXT = "Sudden error at work.\nContact the manager, please!";

    public static function createFrontAnswer(mixed $data = null, string $status = self::OK_STATUS):string {
        return json_encode(
            [
                'status' => $status,
                'data' => $data
            ],
            JSON_UNESCAPED_UNICODE
        );
    }
}
