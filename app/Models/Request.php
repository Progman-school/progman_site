<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Request extends Model
{
    const RECEIVED_STATUS = "received";
    const CONFIRMED_STATUS = "confirmed";
    const PROCESSED_STATUS = "processed";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'updated_at',
        'type',
        'hash',
        'contact',
        'application_data',
        'admin_message_id',
        'language',
        'name',
        'status',
        'user_id',
        'product_id',
        'quantity',
        'current_product_price',
        'coupon_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class);
    }

    private function getRepeats()
    {
        return self::where('contact', $this->contact)
            ->where('course_id', $this->courseId);
    }

    public function getRepeatsCount(): int
    {
        return $this->getRepeats()->count();
    }

    public function getDateOfTheFirstRepeat(): int
    {
        return $this->getRepeats()->first()->created_at;
    }
}
