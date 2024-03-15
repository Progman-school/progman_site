<?php

namespace App\Models;

use App\Models\Coupon\CouponType;
use App\Services\TagService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Request as UserRequest;

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
        'coupon_type_id',
        'course_id',
    ];

    public function getName(): string
    {
        if ($this->course()->exists()) {
            return "{$this->name} \"{$this->course->name}\"";
        }
        return $this->name;
    }

    public function couponType(): BelongsTo
    {
        return $this->belongsTo(CouponType::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function request(): HasMany
    {
        return $this->hasMany(UserRequest::class);
    }

    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class);
    }

}
