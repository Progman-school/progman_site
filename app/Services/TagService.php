<?php

namespace App\Services;

use App\Exceptions\UserAlert;
use App\Models\Tag;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class TagService extends MainService
{
    const LANG_SESSION_KEY = 'lang';
    const EN_LANGUAGE = 'en';
    const RU_LANGUAGE = 'ru';

    const LANG_LIST = [
        self::EN_LANGUAGE,
        self::RU_LANGUAGE,
    ];

    const DEFAULT_LANGUAGE = self::EN_LANGUAGE;

    private const REFRESH_CONTENT_HOURS = 24;
    private const ONE_HOUR = 3600;

    private const REPLACEABLE_TAG_PATTERN = "/\{\{(\w+)\}\}/ui";

    public static function getCurrentLanguage(): string
    {
        return Session::get(self::LANG_SESSION_KEY) ?? self::DEFAULT_LANGUAGE;
    }

    /**
     * @throws Exception
     */
    public static function setCurrentLanguage(string $language): string
    {
        if (!in_array($language, self::LANG_LIST)) {
            throw new Exception("Language '{$language}' is not supported.");
        }
        Session::put(self::LANG_SESSION_KEY, $language);
        if(!$currentLangKey = Session::get(self::LANG_SESSION_KEY)) {
            throw new Exception("Can't set language to session.");
        }
        return $currentLangKey;
    }

    /**
     * @throws Exception
     */
    public static function switchTagLanguage(): string {
        $currentLangKey = array_search(
            self::getCurrentLanguage(),
            self::LANG_LIST
        );
        $currentLang = self::LANG_LIST[$currentLangKey + 1] ?? self::LANG_LIST[0];
        return self::setCurrentLanguage($currentLang);
    }

    private static function isOld(int $contentTime): bool {
        return ($contentTime + (self::REFRESH_CONTENT_HOURS * self::ONE_HOUR) - time()) < 0;
    }

    /**
     * @param string $name
     * @param int $time (if time === 0: get data anyway)
     * @param array $injectionContent
     * @return array
     * @throws UserAlert
     */
    public static function getTagValueByName(string $name, int $time = 0, array $injectionContent = []): array
    {
        /** @var Tag $tag */
        $tag = Tag::where("show", 1)->where("name",  $name)->first();
        if (!$tag) {
            Log::error("The tag of content is not found in DB: {$name}. Try to add the tag and its content values to DB or provide that as an injectionContent.");
            throw new UserAlert("Sorry, Some minor error with content preloading.");
        }

        $tagDataWithValues = ["timeStamp" => time()];
        foreach ($tag->tagValues as $tagValue) {
            foreach (self::LANG_LIST as $lang) {
                if ($tagValue->content == $lang) {
                    if (!empty($injectionContent)) {
                        foreach ($injectionContent as $injectionTagName => $injection) {
                            $tagValue->value = str_replace(
                                "{{{$injectionTagName}}}",
                                    $injection[$lang] ?? $injection[self::DEFAULT_LANGUAGE],
                                $tagValue->value
                            );
                        }
                    }
                    if (preg_match_all(self::REPLACEABLE_TAG_PATTERN, $tagValue->value, $subTags)) {
                        $subTagValues = [];
                        foreach (array_values($subTags[1]) as $subTagKey => $subTag) {
                            $subTagData = self::getTagValueByName($subTag);
                            if ($subTagData) {
                                $subTagValues[] = $subTagData[$lang] ?? $subTagData[self::DEFAULT_LANGUAGE];
                            } else {
                                unset($subTags[0][$subTagKey]);
                            }
                        }
                        $tagValue->value = str_replace(array_values($subTags[0]), $subTagValues, $tagValue->value);
                    }
                    $tagDataWithValues[$lang] = $tagValue?->value;
                }
            }
        }
        return $tagDataWithValues;
    }

    public static function setDefaultLanguageForNulls(array &$tagList): array {
        $langList = [];
        foreach ($tagList as $tag => &$oneTagData) {
            foreach ($oneTagData as $fieldKey => &$fieldValue) {
                if (in_array($fieldKey, self::LANG_LIST)) {
                    $langList[$fieldKey][$tag] =
                        $fieldValue ?: $oneTagData[self::DEFAULT_LANGUAGE];
                }
            }
        }
        return $langList;
    }

    public static function getAllTags(array $tagList = []): array {
        $answer = [];
        $tags = Tag::where("show", 1)->get();
        foreach ($tags as $oneTag) {
            foreach ($oneTag->tagValues as $tagValue) {
                if ($tagValue?->content == 'url') {
                    $tagList[$oneTag->name]['file_url'] = $tagValue->value;
                }
                $tagList[$oneTag->name][$tagValue->content] = $tagValue->value;;
            }
        }

        $langList = self::setDefaultLanguageForNulls($tagList);
        foreach ($tagList as $tag => $oneTagData) {
            foreach ($oneTagData as $fieldKey => $fieldValue) {
                if (isset($langList[$fieldKey])) {
                    $tagMasks = array_map(
                        function ($val) {
                            return "{{{$val}}}";
                        },
                        array_keys($langList[$fieldKey])
                    );
                    $count = 1;
                    while ($count) {
                        $fieldValue = str_replace(
                            $tagMasks,
                            array_values($langList[$fieldKey]),
                            $fieldValue,
                            $count
                        );
                    }
                    $answer[$tag][$fieldKey] = $fieldValue;
                    $answer[$tag]['timeStamp'] = time();
                }
            }
        }
        return $answer;
    }

    /**
     * @throws UserAlert
     */
    public static function getLanguageLocateMetaTagsContents(): array {
        $locateMetaTags = [
            "language_locate_meta_tag_description",
            "language_locate_meta_tag_itemprop_name",
            "language_locate_meta_tag_keywords",
        ];
        $localeMateTags = [];
        foreach ($locateMetaTags as $tag) {
            $localeMateTags[$tag] = self::getTagValueByName($tag)[self::getCurrentLanguage()] ?? null;
        }
        return $localeMateTags;
    }
}
