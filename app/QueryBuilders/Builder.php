<?php

namespace App\QueryBuilders;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

abstract class Builder extends EloquentBuilder
{
    protected array $searchFields = [];

    public function search(?string $phrase = null): static
    {
        if ($phrase) {
            $this->where(function ($query) use ($phrase) {
                foreach ($this->searchFields as $field) {
                    $query->orWhere($field, 'LIKE', "%$phrase%");
                }
            });
        }
        return $this;
    }

    public function sortByString(?string $string = null)
    {
        if ($string) {
            $order = $string[0] === '-' ? 'desc' : 'asc';
            $col = $string[0] === '-' ? substr($string, 1) : $string;
            return $this->orderBy($col, $order);
        }
        return $this;
    }

    /**
     * Run the query as a "select" statement against the connection.
     *
     * @return array
     */
    protected function runSelect()
    {
        return Cache::store('request')->remember($this->getCacheKey(), 10, function() {
            return parent::runSelect();
        });
    }

    /**
     * Returns a Unique String that can identify this Query.
     *
     * @return string
     */
    protected function getCacheKey()
    {
        return json_encode([
            $this->toSql() => $this->getBindings()
        ]);
    }
}
