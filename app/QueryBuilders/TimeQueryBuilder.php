<?php

namespace App\QueryBuilders;

class TimeQueryBuilder extends Builder
{
    public function pending(): self
    {
        return $this->whereNull('end_at');
    }
}
