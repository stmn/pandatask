<?php

namespace App\Models;

use App\QueryBuilders\GroupQueryBuilder;
use App\QueryBuilders\UserQueryBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Scout\Searchable;
use Laravolt\Avatar\Avatar;

class Group extends Model
{

    protected $fillable = [
        'name',
        'color'
    ];

    public static function query(): Builder|GroupQueryBuilder
    {
        return parent::query();
    }

    public function newEloquentBuilder($query): GroupQueryBuilder
    {
        return new GroupQueryBuilder($query);
    }

    public function users()
    {
        return $this->hasManyThrough(User::class, 'group_users');
    }

}
