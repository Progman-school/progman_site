<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
* @method static \Illuminate\Database\Query\Builder|Tag where($value, $operator, $value)
* @method static \Illuminate\Database\Query\Builder|Tag findOne($value)

 */
class Tag extends Model
{

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'type',
        'description',
        'show',
        'order',
    ];

    public function tagValues(): BelongsToMany
    {
       return $this->belongsToMany(TagValue::class);
    }

}
