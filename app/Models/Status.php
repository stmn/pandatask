<?php

namespace App\Models;

use App\QueryBuilders\StatusQueryBuilder;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @mixin IdeHelperStatus
 */
class Status extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
    ];

    public const CREATED_AT = null;
    public const UPDATED_AT = null;

    public static function query(): Builder|StatusQueryBuilder
    {
        return parent::query();
    }

    public function newEloquentBuilder($query): StatusQueryBuilder
    {
        return new StatusQueryBuilder($query);
    }

    public function tasks(): HasMany|StatusQueryBuilder
    {
        return $this->hasMany(Task::class);
    }
}
