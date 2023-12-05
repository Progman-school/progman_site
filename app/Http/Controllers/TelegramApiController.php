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

    public function entry(Request $request): ?string
    {
        if ($request->get("callback_query")) {
            return $this->telegramApiService->manageEntryCallbackQuery($request);
        } elseif ($request->get("message")->get("entities")->get("type") == "bot_command") {
            return $this->telegramApiService->manageEntryCommand($request);
        } elseif ($request->get("message")) {
            return $this->telegramApiService->manageEntryMessage($request);
        } else {
            abort(404);
        }
    }

    public function setHook(Request $request): ?string
    {
        $set = $request->get("setWebhook");
        if (is_null($set)) {
            abort(404);
        }
        return AppHelper::printOnScreen(
            $this->telegramApiService->setHook((bool) $set),
            true
        );
    }
}
