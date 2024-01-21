<?php

namespace App\Models;

use App\Services\TagService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Course extends Model
{
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        "description_" . TagService::EN_LANGUAGE,
        "description_" . TagService::RU_LANGUAGE,
        'level',
        'type',
    ];

    protected $hidden = [
//        'pivot'
    ];

    public function technologies(): BelongsToMany
    {
        return $this->belongsToMany(Technology::class)->withPivot('hours');
    }

    public function requests(): BelongsToMany
    {
        return $this->belongsToMany(Request::class);
    }
}
