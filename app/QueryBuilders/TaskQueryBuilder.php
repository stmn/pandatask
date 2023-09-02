<?php

namespace App\QueryBuilders;

use App\Enums\ActivityType;
use App\Models\User;

final class TaskQueryBuilder extends Builder
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

    public function forUser(User $user): self
    {
        if ($user->isClient()) {
             $this->where('private', 0);
        }

        if(!$user->isAdmin()) {
            $this->whereIn('project_id', $user->projects()->pluck('id'));
        }

        return $this;
    }

    public function forCurrentUser(): self
    {
        return $this->forUser(loggedUser());
    }
}
