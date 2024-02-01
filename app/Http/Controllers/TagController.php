<?php

namespace App\Http\Controllers;

use App\Exceptions\UserAlert;
use App\Helpers\ApiHelper;
use App\Services\TagService;
use Exception;
use Illuminate\Http\Request;

class TagController extends MainController
{
    public function getCurrentLanguage(Request $request):string {
        return ApiHelper::createFrontAnswer(TagService::getCurrentLanguage());
    }

    /**
     * @throws Exception
     */
    public function switchTagLanguage(Request $request):string {
        return ApiHelper::createFrontAnswer(TagService::switchTagLanguage());
    }

    /**
     * @throws Exception
     */
    public function changeTagLanguageTo(string $language):string {
        return ApiHelper::createFrontAnswer(TagService::setCurrentLanguage($language));
    }

    public function getAllTags(Request $request):string {
        return ApiHelper::createFrontAnswer(TagService::getAllTags());
    }

    /**
     * @throws Exception
     */
    public function getTagValueByName(Request $request): string
    {
        return ApiHelper::createFrontAnswer(TagService::getTagValueByName($request->tag, $request->timeStamp));
    }

    /**
     * @throws UserAlert
     */
    public function getLanguageLocateMetaTagsContents(Request $request): string
    {
        return ApiHelper::createFrontAnswer(TagService::getLanguageLocateMetaTagsContents());
    }
}
