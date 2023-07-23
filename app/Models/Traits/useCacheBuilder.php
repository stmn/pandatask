<?php

namespace App\Models\Traits;

use App\Models\Activity;
use App\Models\CacheBuilder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait useCacheBuilder
{
    /**
     * Get a new query builder instance for the connection.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    protected function newBaseQueryBuilder()
    {
        $conn = $this->getConnection();

        $grammar = $conn->getQueryGrammar();

        return new CacheBuilder($conn, $grammar, $conn->getPostProcessor());
    }
}
