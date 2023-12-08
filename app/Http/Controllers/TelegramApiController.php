<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Services\TelegramApiRequestManageService;
use Illuminate\Http\Request;

class TelegramApiController
{
    public function __construct(
        protected TelegramApiRequestManageService $telegramApiRequestManageService,
    ) {}

    public function entry(Request $request): ?string
    {
        if ($request->get("callback_query")) {
            return $this->telegramApiRequestManageService->manageEntryCallbackQuery($request);
        } elseif ($request->get("message")->get("entities")->get("type") == "bot_command") {
            return $this->telegramApiRequestManageService->manageEntryCommand($request);
        } elseif ($request->get("message")) {
            return $this->telegramApiRequestManageService->manageEntryMessage($request);
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
            $this->telegramApiRequestManageService->setHook((bool) $set),
            true
        );
    }
}
