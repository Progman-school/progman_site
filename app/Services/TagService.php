<?php

namespace App\Services;

use App\Models\Tag;
use Illuminate\Support\Facades\Session;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;

/**
 * @mixin EloquentBuilder
 * @mixin QueryBuilder
 */
class TagService
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

    public function __construct(
        protected Tag $tag,
    ) {}

    public function checkCurrentLanguage(): string
    {
        return Session::get(self::LANG_SESSION_KEY) ?? self::DEFAULT_LANGUAGE;
    }

    private static function isOld(int $contentTime): bool {
        return ($contentTime + (self::REFRESH_CONTENT_HOURS * self::ONE_HOUR) - time()) < 0;
    }

    public static function getByName(string $name, int $time, array $injectionContent = []): array|int|null
    {
        if (!self::isOld($time)) {
            return 1;
        }
        return Tag::findOne()->where("name", $name);
    }

    public static function getDataByTagAndLang(string $tag, int $time, array $injectionContent = []): string|int|null
    {
        return self::getByTag($tag, $time, $injectionContent)[Session::get(self::LANG_SESSION_KEY) ?? self::DEFAULT_LANGUAGE];
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
        $tags = Tag::getAll();

        foreach ($tags as $oneTag) {
            foreach ($oneTag->tagValues() as $tagValue) {
                if ($tagValue->content == 'url') {
                    $tagList[$oneTag->name]['file_url'] = $tagValue;
                }
                $tagList[$oneTag->name][$tagValue->content] = $tagValue;
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
}
