<?php

namespace App\Models;

use App\QueryBuilders\ProjectQueryBuilder;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\EloquentSortable\SortableTrait;

/**
 * @mixin IdeHelperPriority
 */
class Priority extends Model
{
    use SoftDeletes,
        SortableTrait;

    protected $fillable = [
        'name',
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
