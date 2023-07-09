<?php

namespace App\Models\Traits;

use App\Models\Activity;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait useLatestActivity
{
    public function latestActivity(): BelongsTo
    {
        return $this->belongsTo(Activity::class);
    }
}
