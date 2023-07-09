<?php

namespace App\QueryBuilders;

use App\Enums\ActivityType;

class TaskQueryBuilder extends Builder
{
    protected array $searchFields = [
        'number',
        'subject',
    ];

    public function withCommentsCount(): self
    {
        return $this->withCount([
            'activities as comments_count' => fn(ActivityQueryBuilder $query) => $query->whereType(ActivityType::TASK_COMMENTED)
        ]);
    }
}
