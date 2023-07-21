<?php

namespace App\QueryBuilders;

use App\Models\User;

final class TimeQueryBuilder extends Builder
{
    public function pending(): self
    {
        return $this->whereNull('end_at');
    }

    public function forUser(User $user): self
    {
        if ($user->isClient()) {
            $this->whereHas('task', fn(TaskQueryBuilder $query) => $query->where('private', 0));
        }

        return $this;
    }

    public function forCurrentUser(): self
    {
        return $this->forUser(loggedUser());
    }
}
