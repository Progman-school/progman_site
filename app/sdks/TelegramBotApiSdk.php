<?php

namespace App\sdks;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class TelegramBotApiSdk
{
    const API_URL = 'https://api.telegram.org/bot';

    const API_ROUT = "tg_api";

    const API_ENTRYPOINT = "/entry";

    private string $token;

    protected Request $request;

    protected string $adminChatId;

    protected array $adminsList;

    function __construct(?Request $request = null) {
        $this->token = config("services.telegram.bot_token");
        $this->adminChatId = config("services.telegram.admin_chat_id");
        $this->adminsList = config("services.telegram.admins_list");;
        $this->request = $request ?? new Request();
    }

    public function getTelegramApi(string $method, array $options = null): mixed {
        $str_request = self::API_URL . $this->token . '/' . $method;
        if ($options) {
            $str_request .= '?' . http_build_query($options);
        }

        Log::notice("PRE TG API request: \n" . print_r([
                "method" => $method,
                "params" => $options,
            ], true));

        $response = file_get_contents($str_request);
        $response_data = json_decode($response, 1) ?? $response;

        Log::notice("TG API request: \n" . print_r([
            "method" => $method,
            "params" => $options,
            "result" => $response_data
        ], true));

        return $response_data;
    }

    public function sendMessage(
        string $text,
        string $chatId,
        array $keyboardArray = null,
        array $extraOptions = null,
    ): mixed {
        $options['text'] = $text;
        $options['chat_id'] = $chatId;
        if ($keyboardArray) {
            $options['reply_markup'] = $this->makeInlineKeyboard($keyboardArray);
        }
        if ($extraOptions) {
            $options = array_merge($options, $extraOptions);
        }
        return $this->getTelegramApi('sendMessage', $options);
    }

    public function editKeyboardOfMessage(
        string $chatId,
        string $messageId,
        array $newKeyboardArray = null
    ): mixed {
        $options['chat_id'] = $chatId;
        $options['message_id'] = $messageId;
        $options['reply_markup'] = $this->makeInlineKeyboard($newKeyboardArray);
        return $this->getTelegramApi('editKeyboardOfMessage', $options);
    }

    public function sendEchoMessage(string $text, array $keyboardArray = null): mixed {
        return $this->sendMessage(
            $text,
            $this->request['message']['chat']['id'],
            $keyboardArray
        );
    }

    public function sendMessageToAdminChat(string $text, array $keyboardArray = null, array $extraOptions = null) {
        return $this->sendMessage(
            $text,
            $this->adminChatId,
            $keyboardArray,
            $extraOptions
        );
    }

    public function editMessageByChatId(string $chatId, int $messageId, string $text, array $keyboardArray = null, array $extraOptions = null) {
        $options['chat_id'] = $chatId;
        $options['message_id'] = $messageId;
        $options['text'] = $text;
        if ($keyboardArray) {
            $options['reply_markup'] = $this->makeInlineKeyboard($keyboardArray);
        }
        if ($extraOptions) {
            $options = array_merge($options, $extraOptions);
        }
        return $this->getTelegramApi('editMessageText', $options);
    }

    public function editMessageInAdminChat(int $messageId, string $text, array $keyboardArray = null, array $extraOptions = null) {
        return $this->editMessageByChatId(
            $this->adminChatId,
            $messageId,
            $text,
            $keyboardArray,
            $extraOptions
        );
    }

    public function makeInlineKeyboard(array $keyboardArray): string {
        $buttons = [];
        foreach ($keyboardArray as $name => $callbackData) {
            $buttons[] = ['text' => $name, 'callback_data' => $callbackData];
        }
        return json_encode(["inline_keyboard" => [$buttons]]);
    }

    public function setHook($set = 1) {
        $uri = "{$_SERVER['REQUEST_SCHEME']}://{$_SERVER['HTTP_HOST']}/"
            . self::API_ROUT . self::API_ENTRYPOINT;

        return self::getTelegramApi(
            'setWebhook',
            ['url' => $set ? $uri : '']
        ) + ["url" => $uri];
    }
}
