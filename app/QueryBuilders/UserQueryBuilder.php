<?php

namespace App\QueryBuilders;

class UserQueryBuilder extends Builder
{
    protected $searchFields = [
        'id',
        'first_name',
        'last_name',
        'public_email',
    ];
}
