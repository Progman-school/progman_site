<?php

namespace App\Http\Controllers;

use App\Helper;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;
use Throwable;

class MainController extends BaseController
{
    protected const ERROR_STATUS = 'error';
    protected const OK_STATUS = "ok";
    protected const DEFAULT_ERROR_TEXT = "Sudden error at work.\nContact the manager, please!";

    protected static function do(mixed $result, string $errorText = self::DEFAULT_ERROR_TEXT): string
    {
        try {
            return Helper::createFrontAnswer(self::OK_STATUS, $result);
        } catch (Throwable $e) {
            if ($e->getCode() == 7) {
                return Helper::createFrontAnswer(self::ERROR_STATUS, $e->getMessage());
            }
            Log::error(print_r($e->getMessage(), true));
            return Helper::createFrontAnswer(self::ERROR_STATUS, $errorText);
        }
    }
}
