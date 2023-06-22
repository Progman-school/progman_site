<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;

class MainService
{
    public function __construct(
        protected Model $model,
    ) {}
}
