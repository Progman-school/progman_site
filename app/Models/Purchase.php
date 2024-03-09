<?php

namespace App\Models;

use App\Models\Coupon\CouponType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Request as UserRequest;

class Purchase extends Model
{
    const AUTO_PAYMENT_TYPE = "auto";
    const MANAGER_PAYMENT_TYPE = "manager";

    const PAYMENT_TYPES = [
        self::AUTO_PAYMENT_TYPE,
        self::MANAGER_PAYMENT_TYPE
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'request_id',
        'product_id',
        'coupon_id',
        'quantity',
        'current_product_price',
        'total_price',
        'contact',
        'user_id',
        'payment_type',
        'payment_details',
        'admin_id',
        'comment',
    ];

    public function coupon(): HasOne
    {
        return $this->hasOne(Coupon::class);
    }

    public function request(): HasOne
    {
        return $this->hasOne(UserRequest::class);
    }

    public function product(): HasOne
    {
        return $this->hasOne(Product::class);
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }

}
