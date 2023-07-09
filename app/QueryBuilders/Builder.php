<?php

namespace App\QueryBuilders;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

class Builder extends EloquentBuilder
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
}
