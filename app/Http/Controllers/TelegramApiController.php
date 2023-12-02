<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Services\TelegramApiService;
use Illuminate\Http\Request;

class TelegramApiController
{
    public function __construct(
        protected TelegramApiService $telegramApiService,
    ) {}

    public function setHook(Request $request): ?string
    {
        return AppHelper::printOnScreen(
            $this->telegramApiService->setHook((bool) $request->get("set", 1)),
            true
        );
    }
}
