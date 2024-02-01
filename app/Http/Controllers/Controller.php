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

    /**
     * @throws UserAlert
     * @throws \Exception
     */
    public static function index(?string $lang = null) {
        $localeMateTags = null;

        if ($lang) {
            App::setLocale($lang);
            TagService::setCurrentLanguage($lang);
            $localeMateTags = TagService::getTagValueByName(
                "language_locate_meta_tags"
            )[TagService::getCurrentLanguage()] ?? "";
        }

        return view('index', ["locate_mete_tags" => $localeMateTags ?? ""]);
    }
}
