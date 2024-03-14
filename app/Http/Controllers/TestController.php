<?php

namespace App\Http\Controllers;

use App\Exceptions\UserAlert;
use App\Helpers\ApiHelper;
use App\Services\TestService;
use Illuminate\Http\Request;

class TestController extends MainController
{

    /**
     * @throws UserAlert
     */
    public function processTest(Request $request): string
    {
        return ApiHelper::createFrontAnswer(
            TestService::processTest($request->toArray())
        );
    }
}
