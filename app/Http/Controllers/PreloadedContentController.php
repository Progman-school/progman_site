<?php

namespace App\Http\Controllers;

use App\Helpers\ApiHelper;
use App\Services\CourseService;
use Illuminate\Http\Request;

class PreloadedContentController extends MainController
{
    public function getCursesList():string {
        return ApiHelper::createFrontAnswer(CourseService::getCursesList());
    }
}
