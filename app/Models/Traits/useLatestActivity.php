<?php

namespace App\Models\Traits;

use App\Models\Activity;

trait useLatestActivity
{
    public function latestActivity()
    {
        return $this->belongsTo(Activity::class);
    }
}
