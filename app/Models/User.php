<?php

namespace App\Models;

use App\Models\Uids\Email;
use App\Models\Uids\Telegram;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'created_at',
        'updated_at',
        'first_name',
        'last_name',
        'real_last_name',
        'real_first_name',
        'real_middle_name',
        'telegram',
        'email',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function requests(): BelongsToMany
    {
        return $this->belongsToMany(Request::class);
    }

    public function telegrams(): HasMany
    {
        return $this->hasMany(Telegram::class);
    }

    public function emails(): HasMany
    {
        return $this->hasMany(Email::class);
    }
}
