<?php

namespace App\QueryBuilders;

use App\Models\Traits\useCacheBuilder;

final class GroupQueryBuilder extends Builder
{
//    use useCacheBuilder;

    protected array $searchFields = [
        'name'
    ];
}
