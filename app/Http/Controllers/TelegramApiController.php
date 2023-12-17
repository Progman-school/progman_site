<?php

namespace App\Http\Controllers;

use App\Exceptions\UserAlert;
use App\Helpers\AppHelper;
use App\Services\TelegramRequestService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TelegramApiController
{
    public function __construct(
        protected TelegramRequestService $telegramApiRequestManageService,
    ) {}

    /**
     * @throws UserAlert
     */
    public function entry(Request $request): void
    {
        if ($request->get("callback_query")) {
            $this->telegramApiRequestManageService->manageEntryCallbackQuery($request);
        } elseif ($request->get("message")?->get("entities")?->get("type") == "bot_command") {
            $this->telegramApiRequestManageService->manageEntryCommand($request);
        } elseif ($request->get("message")) {
            $this->telegramApiRequestManageService->manageEntryMessage($request);
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
