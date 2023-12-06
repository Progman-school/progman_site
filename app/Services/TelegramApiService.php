<?php

namespace App\Services;

use App\Exceptions\UserAlert;
use App\Models\User;
use \App\Models\Request as UserRequest;
use Illuminate\Http\Request;

class TelegramApiService
{
    const API_URL = 'https://api.telegram.org/bot';

    const API_ROUT = "tg_api";

    const API_ENTRYPOINT = "entry";

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



    public function manageEntryCommand(Request $request): mixed {
        $message = $request->get("message")->text;
        $offset = $request->get("message")->entities->length;
        $command_params = trim(substr($message, $offset));
        switch (substr($message, 0, $offset)) {  // check command name
            case '/start':




                break;
            case "how yes":
                break;
            case "how no":
                break;
            case "how all":
                break;
            default:
                abort(404);
        }

        return json_decode($response, 1) ?? $response;
    }

    public function manageEntryMessage(Request $request): mixed {
        switch ($request->get("message")->get("text")) {
            case mb_stripos($input['message']['text'], '/start ') !== false:

                break;
            case "how yes":
                break;
            case "how no":
                break;
            case "how all":
                break;
            default:
                abort(404);
        }

        return json_decode($response, 1) ?? $response;
    }

    public function manageEntryCallbackQuery(string $method, array $options = null): mixed {
        $str_request = self::API_URL . $this->token . '/' . $method;
        if ($options) {
            $str_request .= '?' . http_build_query($options);
        }
        $response = file_get_contents($str_request);
        return json_decode($response, 1) ?? $response;
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
        $uri = "{$_SERVER['REQUEST_SCHEME']}://{$_SERVER['HTTP_HOST']}/"
            . self::API_ROUT . "/" . self::API_ENTRYPOINT;
        return self::getTelegramApi(
            'setWebhook',
            ['url' => $set ? $uri : '']
        );
    }
}
