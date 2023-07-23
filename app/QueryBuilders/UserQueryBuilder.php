<?php

namespace App\QueryBuilders;

use App\Models\Traits\useCacheBuilder;

final class UserQueryBuilder extends Builder
{
//    use useCacheBuilder;

    protected array $searchFields = [
        'id',
        'first_name',
        'last_name',
        'public_email',
    ];

    public function whereHasGroupType(string $type): self
    {
        return $this->whereHas('groups', static function (Builder $query) use ($type) {
            $query->where('type', $type);
        });
    }
}
