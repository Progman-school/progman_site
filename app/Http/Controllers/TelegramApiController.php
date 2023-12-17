<?php

namespace App\Http\Controllers;

use App\Exceptions\UserAlert;
use App\Helpers\AppHelper;
use App\Services\TelegramRequestService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TelegramApiController extends MainController
{

    /**
     * @throws UserAlert
     */
    public function entry(Request $request): void
    {
        $telegramApiRequestManageService = new TelegramRequestService($request);
        $callbackQuery = $request->get("callback_query");
        $messageData = $request->get("message");
        if (!empty($callbackQuery)) {
            $telegramApiRequestManageService->manageEntryCallbackQuery();
        }
        elseif (isset($messageData["entities"], $messageData["entities"]["type"]) && $messageData["entities"]["type"] == "bot_command") {
            $telegramApiRequestManageService->manageEntryCommand();
        }
        elseif (isset($messageData["text"])) {
            $telegramApiRequestManageService->manageEntryMessage();
        }
        else {
            abort(404);
        }
    }

    public function setHook(Request $request): ?string
    {
        $telegramApiRequestManageService = new TelegramRequestService($request);
        $set = $request->get("setWebhook");
        if (is_null($set)) {
            abort(404);
        }
        return AppHelper::printOnScreen(
            $telegramApiRequestManageService->setHook((bool) $set),
            true
        );
    }
}
