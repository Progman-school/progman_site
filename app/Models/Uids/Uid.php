<?php

namespace App\Models\Uids;

use Illuminate\Database\Eloquent\Model;

class Uid extends Model
{
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'service_uid',
        'service_login',
        'data',
    ];
}
