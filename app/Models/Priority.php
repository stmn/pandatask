<?php

namespace App\Models;

use App\Models\Traits\useSortableTrait;
use App\QueryBuilders\ProjectQueryBuilder;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @mixin IdeHelperPriority
 * @property int $order_number
 */
class Priority extends Model
{
    use SoftDeletes,
        useSortableTrait;

    protected $fillable = [
        'name',
        'order_number',
        'color',
    ];

    public const CREATED_AT = null;
    public const UPDATED_AT = null;

    public static function query(): Builder|ProjectQueryBuilder
    {
        return parent::query();
    }

    public function newEloquentBuilder($query): ProjectQueryBuilder
    {
        return new ProjectQueryBuilder($query);
    }

    public function tasks(): HasMany|ProjectQueryBuilder
    {
        return $this->hasMany(Task::class);
    }
}
