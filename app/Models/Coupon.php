<?php

namespace App\Models;

use App\Models\Coupon\CouponPlacement;
use App\Models\Coupon\CouponType;
use App\Models\Coupon\CouponUnit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Request as UserRequest;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Coupon extends Model
{
    const CUSTOM_METHOD = "custom";
    const GENERATED_METHOD = "generated";
    const AUTO_GENERATED_METHOD = "auto_generated";

    const METHODS = [
        self::CUSTOM_METHOD,
        self::GENERATED_METHOD,
        self::AUTO_GENERATED_METHOD
    ];

    const ONLINE_AREA_TYPE= "online";
    const OFFLINE_AREA_TYPE= "offline";
    const AREA_TYPES = [
        self::ONLINE_AREA_TYPE,
        self::OFFLINE_AREA_TYPE
    ];


    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'serial_number',
        'description',
        'language',
        'method',
        'coupon_type_id',
        'expired_at',
        'value',
        'coupon_unit_id',
        'max_times',
        'used_times',
        'area_type',
        'area',
        'placement_id',
        'is_active',
    ];

    protected $hidden = [
    ];

    public function couponType(): BelongsTo
    {
        return $this->belongsTo(CouponType::class);
    }

    public function couponUnit(): BelongsTo
    {
        return $this->belongsTo(CouponUnit::class);
    }

    public function placement(): BelongsTo
    {
        return $this->belongsTo(CouponPlacement::class);
    }

    public function requests(): HasMany
    {
        return $this->hasMany(UserRequest::class);
    }

    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class);
    }

    public function testResult(): HasOne
    {
        return $this->hasOne(TestResult::class);
    }

}
