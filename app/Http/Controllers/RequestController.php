<?php

namespace App\Http\Controllers;

use App\Helpers\APIHelper;
use App\Services\RequestService;
use Illuminate\Http\Request;

class RequestController
{
    public function addRequest(Request $request): ?string
    {
        return APIHelper::createFrontAnswer(
            RequestService::addRequest($request)
        );
    }
}
