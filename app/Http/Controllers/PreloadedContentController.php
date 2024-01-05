<?php

namespace App\Http\Controllers;

use App\Helpers\ApiHelper;
use App\Services\CourseService;
use Illuminate\Http\Request;

class PreloadedContentController extends MainController
{
    public function getCoursesList():string {
        return ApiHelper::createFrontAnswer(CourseService::getCoursesList(true));
    }
}
