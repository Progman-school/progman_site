<?php

namespace App\Models\Coupon;

use App\Models\Coupon;
use App\Services\TagService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CouponUnit extends Model
{
    public $timestamps = false;

    const SYMBOL_PLACEMENT_BEFORE = 'before';
    const SYMBOL_PLACEMENT_AFTER = 'after';
    const SYMBOL_PLACEMENTS = [
        self::SYMBOL_PLACEMENT_BEFORE,
        self::SYMBOL_PLACEMENT_AFTER,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'symbol',
        "symbol_placement",
        "formula",
    ];

    protected $hidden = [
    ];

    public function coupons(): HasMany
    {
        return $this->hasMany(Coupon::class);
    }

}
