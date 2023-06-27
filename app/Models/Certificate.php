<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Certificate extends Model
{
    public function course(): HasOne
    {
        return $this->hasOne(Course::class);
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }
}
