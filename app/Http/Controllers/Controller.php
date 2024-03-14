<?php

namespace App\Http\Controllers;

use App\Exceptions\UserAlert;
use App\Services\TagService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\App;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
//  "REDIRECT_QUERY_STRING" => "coupon=VIP-32B6364D"
    /**
     * @throws UserAlert
     * @throws \Exception
     */
    public static function index(?string $lang = null) {
        $localeMateTags = [];
        if ($lang && in_array($lang, TagService::LANG_LIST)) {
            $lang = in_array($lang, TagService::LANG_LIST) ? $lang : TagService::getCurrentLanguage();
            App::setLocale($lang);
            TagService::setCurrentLanguage($lang);
            $localeMateTags = TagService::getLanguageLocateMetaTagsContents();
        }
        return view('index', ["locateMetaTags" => $localeMateTags]);
    }
}
