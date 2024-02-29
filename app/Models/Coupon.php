<?php

namespace App\Models;

use App\Models\Coupon\CouponPlacement;
use App\Models\Coupon\CouponType;
use App\Models\Coupon\CouponUnit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Coupon extends Model
{
    const CUSTOM_METHOD = "custom";
    const GENERATED_METHOD = "generated";
    const METHODS = [
        self::CUSTOM_METHOD,
        self::GENERATED_METHOD
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
    ];

    protected $hidden = [
    ];

    public function type(): HasOne
    {
        return $this->hasOne(CouponType::class);
    }

    public function unit(): HasOne
    {
        return $this->hasOne(CouponUnit::class);
    }

    public function placement(): HasOne
    {
        return $this->hasOne(CouponPlacement::class);
    }

}
