<?php

namespace App\Models;

use App\QueryBuilders\GroupQueryBuilder;
use App\QueryBuilders\UserQueryBuilder;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

/**
 * @mixin IdeHelperGroup
 */
class Group extends Model
{
    protected $fillable = [
        'name',
        'description',
        'color',
    ];

    public static function query(): Builder|GroupQueryBuilder
    {
        return parent::query();
    }

    public function newEloquentBuilder($query): GroupQueryBuilder
    {
        return new GroupQueryBuilder($query);
    }

    public function users(): HasManyThrough|UserQueryBuilder
    {
        return $this->hasManyThrough(User::class, 'group_users');
    }

    protected function can(): Attribute
    {
        return Attribute::make(
            get: fn() => [
                'delete' => loggedUser()->can('delete', $this),
            ],
        );
    }
}
