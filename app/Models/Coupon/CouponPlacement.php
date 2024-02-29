<?php

namespace App\Models\Coupon;

use App\Models\Coupon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CouponPlacement extends Model
{
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
    ];

    protected $hidden = [
//        'pivot'
    ];

    public function coupons(): HasMany
    {
        return $this->hasMany(Coupon::class);
    }

}
