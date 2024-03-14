<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TestResult extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'topic',
        'url',
        'uid_type',
        'contact',
        'name',
//        'user_id',
        'score',
        'result',
        'form_data',
//        'coupon_id',
        'result_message_link_clicked_at',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }
}
