<?php

namespace App\Http\Controllers;

use App\Services\TagService;
use Illuminate\Http\Request;

class TagController extends MainController
{
    public function getCurrentLanguage(Request $request):string {
        return $request->json(self::do(
            TagService::getCurrentLanguage()
        ));
    }

    public function switchLanguage(Request $request):string {
        return $request->json(self::do(
            TagService::switchLanguage()
        ));
    }

    public function getAllContent(Request $request):string {
        return $request->json(self::do(
            TagService::getAllTags()
        ));
    }

    public function getTagDataByName(Request $request): string
    {
        $requestArray =  $request->toArray();
        return $request->json(self::do(
            TagService::getTagDataByName($requestArray["tag"], $requestArray['timeStamp'])
        ));
    }
}
