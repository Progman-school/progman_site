<?php

namespace App\sdks;

use Illuminate\Http\Request;

class EmailServiceSdk
{
    const API_ROUT = "email_service";

    const API_ENTRYPOINT = "/entry";

    protected Request $request;

    protected string $managerAddress;

    protected string $noReplyAddress;

    function __construct(Request $request){
        $this->managerAddress = config("services.email.manager_address");
        $this->noReplyAddress = config("services.email.no_reply_address");;
        $this->request = $request;
    }

    public function sendMessage() {

    }

}
