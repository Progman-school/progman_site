<?php

namespace App\Http\Controllers;

use App\Helpers\APIHelper;
use App\Services\TagService;
use Exception;
use Illuminate\Http\Request;

class TagController extends MainController
{
    public function getCurrentLanguage(Request $request):string {
        return APIHelper::createFrontAnswer(TagService::getCurrentLanguage());
    }

    public function switchTagLanguage(Request $request):string {
        return APIHelper::createFrontAnswer(TagService::switchTagLanguage());
    }

    public function getAllTags(Request $request):string {
        return APIHelper::createFrontAnswer(TagService::getAllTags());
    }

    /**
     * @throws Exception
     */
    public function getTagValueByName(Request $request): string
    {
        return APIHelper::createFrontAnswer(TagService::getTagValueByName($request->tag, $request->timeStamp));
    }
}
