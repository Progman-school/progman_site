<?php

namespace App\Models\Coupon;

use App\Models\Coupon;
use App\Models\Product;
use App\Services\TagService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CouponType extends Model
{
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'use_link',
        'prefix',
        "description_" . TagService::EN_LANGUAGE,
        "description_" . TagService::RU_LANGUAGE,
    ];

    protected $hidden = [
    ];

    public function coupons(): HasMany
    {
        return $this->hasMany(Coupon::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
