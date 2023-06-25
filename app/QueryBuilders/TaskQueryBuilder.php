<?php

namespace App\QueryBuilders;

class TaskQueryBuilder extends Builder
{
    protected $searchFields = [
        'number',
        'subject',
    ];
}
