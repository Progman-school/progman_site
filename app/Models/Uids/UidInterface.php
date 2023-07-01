<?php

namespace App\Models\Uids;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

interface UidInterface
{
    public function user(): BelongsTo;
}
