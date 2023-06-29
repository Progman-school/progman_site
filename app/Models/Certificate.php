<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Certificate extends Model
{
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'full_number',
        'user_id',
        'course_id',
        'start_date',
        'date',
        'hours',
        'description',
        'language',
        'blank',
    ];

    public function course(): HasOne
    {
        return $this->hasOne(Course::class);
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }

    public function technologies(): BelongsToMany
    {
        return $this->belongsToMany(Technology::class);
    }
}
