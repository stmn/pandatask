<?php

namespace App\QueryBuilders;

use App\Enums\ActivityType;
use App\Models\User;

final class ActivityQueryBuilder extends Builder
{
    public function whereType(ActivityType $type): self
    {
        return $this->where('type', '=', $type);
    }

    public function forUser(User $user): self
    {
        if ($user->isClient()) {
            $this->where('private', 0)
                ->whereHas('task', fn(TaskQueryBuilder $query) => $query->where('private', 0));
        }

        return $this;
    }

    public function forCurrentUser(): self
    {
        return $this->forUser(loggedUser());
    }
}
