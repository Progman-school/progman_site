<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Technology extends Model
{
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'type',
        'description',
    ];
    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class);
    }

    public function certificates(): BelongsToMany
    {
        return $this->belongsToMany(Certificate::class);
    }
}
