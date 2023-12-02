<?php

namespace App\Services;

class TelegramApiService
{

    private string $token;

    private mixed $input;

    private string $admin_chat_id;

    private array $admins_list;

    function __construct($request = null){
        $this->token = config("services.telegram.bot_token");
        $this->admin_chat_id = config("services.telegram.admin_chat_id");
        $this->admins_list = config("services.telegram.admins_list");;

        $this->input = $request;
    }
}
