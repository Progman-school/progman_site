<?php

namespace App\Http\Controllers;

use App\Services\TagService;
use Exception;
use Illuminate\Http\Request;

class TagController extends MainController
{
    public function getCurrentLanguage(Request $request):string {
        return self::do(TagService::getCurrentLanguage());
    }

    public function switchTagLanguage(Request $request):string {
        return self::do(TagService::switchTagLanguage());
    }

    public function getAllTags(Request $request):string {
        return self::do(TagService::getAllTags());
    }

    /**
     * @throws Exception
     */
    public function getTagValueByName(Request $request): string
    {
        return self::do(TagService::getTagValueByName($request->tag, $request->timeStamp));
    }
}
