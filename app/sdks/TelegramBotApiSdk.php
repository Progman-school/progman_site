<?php

namespace App\sdks;

use Illuminate\Http\Request;


class TelegramBotApiSdk
{
    const API_URL = 'https://api.telegram.org/bot';

    const API_ROUT = "tg_api";

    const API_ENTRYPOINT = "/entry";

    private string $token;

    private Request $request;

    private string $adminChatId;

    private array $adminsList;

    function __construct(Request $request){
        $this->token = config("services.telegram.bot_token");
        $this->adminChatId = config("services.telegram.admin_chat_id");
        $this->adminsList = config("services.telegram.admins_list");;
        $this->request = $request;
    }

    public function getTelegramApi(string $method, array $options = null): mixed {
        $str_request = self::API_URL . $this->token . '/' . $method;
        if ($options) {
            $str_request .= '?' . http_build_query($options);
        }
        $response = file_get_contents($str_request);
        return json_decode($response, 1) ?? $response;
    }

    public function sendMessage(
        string $text,
        string $chatId,
        array $keyboardArray = null
    ): mixed {
        $options['text'] = $text;
        $options['chat_id'] = $chatId;
        if ($keyboardArray) {
            $options['reply_markup'] = $this->makeInlineKeyboard($keyboardArray);
        }
        return $this->getTelegramApi('sendMessage', $options);
    }

    public function sendEchoMessage(string $text, array $keyboardArray = null): mixed {
        return $this->sendMessage(
            $text,
            $this->request['message']['chat']['id'],
            $keyboardArray
        );
    }

    public function sendMessageToAdminChat(string $text, array $keyboardArray = null) {
        return $this->sendMessage(
            $text,
            $this->adminChatId,
            $keyboardArray
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
