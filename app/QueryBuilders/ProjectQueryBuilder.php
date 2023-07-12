<?php

namespace App\QueryBuilders;

use App\Models\User;

class ProjectQueryBuilder extends Builder
{
    protected array $searchFields = [
        'name',
        'description'
    ];

    public function forUser(User $user): self
    {
        if ($user->isAdmin()) {
            return $this;
        }

        return $this->whereHas('members', function ($query) use ($user) {
            $query->where('id', $user->id);
        });
    }

    public function forCurrentUser(): self
    {
        return $this->forUser(loggedUser());
    }
}
