<?php

namespace App\Http\Controllers;

use App\Helpers\ApiHelper;
use App\Services\TestService;
use Illuminate\Http\Request;

class TestController extends MainController
{

    public function processTest(Request $request): string
    {
        return ApiHelper::createFrontAnswer(
            TestService::processTest($request->toArray())
        );
    }
}
