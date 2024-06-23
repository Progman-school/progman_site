<?php

namespace App\Models;

use App\Models\Coupon\CouponType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Request as UserRequest;

class Purchase extends Model
{
    const AUTO_PAYMENT_METHOD = "auto";
    const MANAGER_PAYMENT_METHOD = "manager";

    const PAYMENT_METHODS = [
        self::AUTO_PAYMENT_METHOD,
        self::MANAGER_PAYMENT_METHOD
    ];

    const PAYPAL_PAYMENT_TYPE = "paypal";
    const CASH_PAYMENT_TYPE = "cash";
    const ZELLE_PAYMENT_TYPE = "zelle";
    const RUSSIAN_MOBILE_PAYMENT_TYPE = "russian_mobile";

    const PAYMENT_TYPES = [
        self::PAYPAL_PAYMENT_TYPE,
        self::CASH_PAYMENT_TYPE,
        self::ZELLE_PAYMENT_TYPE,
        self::RUSSIAN_MOBILE_PAYMENT_TYPE
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
        'name',
        'user_id',
        'method',
        'payment_type',
        'payment_details',
        'service_fee',
//        'admin_id',
        'comment',
    ];

    public function request(): BelongsTo
    {
        return $this->belongsTo(UserRequest::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
