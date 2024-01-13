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
    public function entry(Request $request): bool
    {
        $telegramApiRequestManageService = new TelegramRequestService($request);

        if ($request->get("callback_query")) {
            return $telegramApiRequestManageService->manageEntryCallbackQuery();
        }
        $messageData = $request->get("message");
        if (isset($messageData["text"])) {
            if (isset($messageData["entities"], $messageData["entities"][0])
                && $messageData["entities"][0]["type"] == "bot_command"
            ) {
                return $telegramApiRequestManageService->manageEntryCommand();
            }
            return $telegramApiRequestManageService->manageEntryMessage();
        }
        return abort(200);
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
