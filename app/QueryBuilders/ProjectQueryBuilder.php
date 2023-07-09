<?php

namespace App\QueryBuilders;

class ProjectQueryBuilder extends Builder
{
    protected array $searchFields = [
        'name',
        'description'
    ];
}
