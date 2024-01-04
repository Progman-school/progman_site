<?php

namespace App\sdks;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailable;

class EmailServiceSdk
{
    const API_ROUT = "email_service";

    const API_ENTRYPOINT = "/entry";

    protected ?Request $request;

    protected string $managerAddress;

    protected string $noReplyAddress;

    function __construct(Request $request = null){
        $this->noReplyAddress = config("mail.from.noreply_address");
        $this->managerAddress = config("mail.from.address");;
        $this->request = $request;
    }

    /**
     * @throws Exception
     */
    public function sendMessage(string $subject, string $body, string|array $address, $isHtml = false): void
    {

    }

    public function sendEchoMessage(string $text) {
        // todo: make it later
    }

}
