<?php

namespace App\Http\Controllers;

use App\Helper;
use App\Models\Tag;
use App\Services\TagService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;


class TagController extends Controller
{
    public function __construct(
        protected TagService $service,
    ) {}



    public function changeLanguage(Request $request):string {
        try {
            $currentLangKey = array_search(
                self::checkCurrentLanguage(),
                self::LANG_LIST
            );
            $currentLang = self::LANG_LIST[$currentLangKey + 1] ?? self::LANG_LIST[0];
            Session::put(self::LANG_SESSION_KEY, $currentLang);
            return Helper::createFrontAnswer($currentLang);
        } catch (\Throwable $e) {
            if ($e->getCode() == 7) {
                return Helper::createFrontAnswer("Error!", $e->getMessage());
            }
            Log::error(print_r($e->getMessage(), true));
            return Helper::createFrontAnswer("Error!", "Sudden error at work.\nContact the manager, please!");
        }
    }

    public function getCurrentLanguage(Request $request):string {
        try {
            return Helper::createFrontAnswer(self::checkCurrentLanguage());
        } catch (\Throwable $e) {
            if ($e->getCode() == 7) {
                return Helper::createFrontAnswer("Error!", $e->getMessage());
            }
            Log::error(print_r($e->getMessage(), true));
            return Helper::createFrontAnswer("Error!", "Sudden error at work.\nContact the manager, please!");
        }
    }

    public function getAllContent(Request $request):string {
        try {
            return Helper::createFrontAnswer('site language content', Tag::getAllContent());
        } catch (\Throwable $e) {
            if ($e->getCode() == 7) {
                return Helper::createFrontAnswer("Error!", $e->getMessage());
            }
            Log::error(print_r($e->getMessage(), true));
            return Helper::createFrontAnswer("Error!", "Sudden error at work.\nContact the manager, please!");
        }
    }

    public function getContentByTag(Request $request): string
    {
        try {
            $request = $request->toArray();
            if (!isset($request['tag'], $request['timeStamp'])) {
                throw new Exception("Incorrect params!");
            }
            return Helper::createFrontAnswer(
                'content data',
                Tag::getByTag($request['tag'], $request['timeStamp'])
            );
        } catch (\Throwable $e) {
            if ($e->getCode() == 7) {
                return Helper::createFrontAnswer("Error!", $e->getMessage());
            }
            Log::error(print_r($e->getMessage(), true));
            return Helper::createFrontAnswer(
                "Error! | " . $e->getMessage(),
                $e->getTraceAsString()
            );
        }
    }
}
