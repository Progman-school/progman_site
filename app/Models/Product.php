<?php

namespace App\Models;

use App\Services\TagService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'created_at',
        'updated_at',
        'name',
        "description_" . TagService::EN_LANGUAGE,
        "description_" . TagService::RU_LANGUAGE,
        'price',
        'image_url',
        'course_id',
    ];

    public function course(): HasOne
    {
        return $this->hasOne(Course::class);
    }
}
