<?php

namespace App\Http\Controllers;

use App\Helpers\ApiHelper;
use App\Services\RequestService;
use Illuminate\Http\Request;

class RequestController
{
    public function addRequest(Request $request): ?string
    {
        return ApiHelper::createFrontAnswer(
            RequestService::addRequest($request)
        );
    }
}
