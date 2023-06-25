<?php

namespace App\QueryBuilders;

class ProjectQueryBuilder extends Builder
{
    protected $searchFields = [
        'name',
        'description'
    ];
}
