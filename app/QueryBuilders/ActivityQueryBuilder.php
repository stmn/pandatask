<?php

namespace App\QueryBuilders;

use App\Enums\ActivityType;

class ActivityQueryBuilder extends Builder
{
    public function whereType(ActivityType $type): self
    {
        return $this->where('type', '=', $type);
    }
}
