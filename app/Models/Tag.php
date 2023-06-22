<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
* @method static \Illuminate\Database\Query\Builder|Tag where($value)
* @method static \Illuminate\Database\Query\Builder|Tag findOne($value)
*/
class Tag extends Model
{

    public string $name;

    public function tagValues(): BelongsToMany
    {
       return $this->belongsToMany(TagValue::class);
    }

}
