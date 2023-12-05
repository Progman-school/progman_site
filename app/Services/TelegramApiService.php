<?php

namespace App\Services;

class TelegramApiService
{
    const API_URL = 'https://api.telegram.org/bot';

    private string $token;

    private mixed $input;

    private string $adminChatId;

    private array $adminsList;

    function __construct($input= null){
        $this->token = config("services.telegram.bot_token");
        $this->adminChatId = config("services.telegram.admin_chat_id");
        $this->adminsList = config("services.telegram.admins_list");;
        $this->input = $input;
    }

    public function getTelegramApi(string $method, array $options = null): mixed {
        $str_request = self::API_URL . $this->token . '/' . $method;
        if ($options) {
            $str_request .= '?' . http_build_query($options);
        }
        $response = file_get_contents($str_request);
        return json_decode($response, 1) ?? $response;
    }

    public function setHook($set = 1) {
        $uri = "{$_SERVER['REQUEST_SCHEME']}://'{$_SERVER['HTTP_HOST']}/tg_api";
        return self::getTelegramApi(
            'setWebhook',
            ['url' => $set ? $uri : '']
        );
    }


}
