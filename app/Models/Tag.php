<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;

/**
* @mixin EloquentBuilder
* @mixin QueryBuilder
*/
class Tag extends Model
{

    public string $name;

    public function tagValues(): BelongsToMany
    {
`        return $this->belongsToMany(TagValue::class);
    }

    /**
     * @return self[]
     */
    public static function getAll(): array
    {
        return self::all()->where('show', 1)->toArray();
    }
}
