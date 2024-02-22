<?php

namespace App\Services;

use App\Exceptions\UserAlert;

class SupportMessengerService
{
    const NEW_SUPPORT_REQUEST_STATUS = "new_support_message";
    const PROCESSED_SUPPORT_REQUEST_STATUS = "processed_support_message";
    const SUPPORT_MESSAGE_STATUS_KEYBOARDS = [
        self::NEW_SUPPORT_REQUEST_STATUS => ['⚠️ new message' => self::PROCESSED_SUPPORT_REQUEST_STATUS],
        self::PROCESSED_SUPPORT_REQUEST_STATUS => ['✅ Answered ✅' => self::NEW_SUPPORT_REQUEST_STATUS],
    ];

    public static function sendSupportRequestMessageToAdminChat(string $senderEmail, string $message, $currentUrl): string
    {
        $telegramRequestService = new TelegramRequestService();
        $messageText = "📧Support request!\n\nEmail: {$senderEmail}\nSite URL: {$currentUrl}\n\nText\n{$message}";
        $result = $telegramRequestService->sendMessageToAdminChat(
            $messageText,
            self::SUPPORT_MESSAGE_STATUS_KEYBOARDS[self::NEW_SUPPORT_REQUEST_STATUS]
        );

        if (!isset($result["ok"])) {
            throw new UserAlert("Error, we can not receive your message. Please, contact to manager.");
        }

        return "Your message has been sent to the support team. We will contact you soon!";
    }

    public static function switchSupportRequestStatus(string $status): string
    {
        return $status;
    }
}
