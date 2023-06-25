<?php

namespace App\Models;

use App\QueryBuilders\ActivityQueryBuilder;
use App\QueryBuilders\ProjectQueryBuilder;
use App\QueryBuilders\TaskQueryBuilder;
use App\QueryBuilders\TimeQueryBuilder;
use App\QueryBuilders\UserQueryBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravolt\Avatar\Avatar;

/**
 * @mixin IdeHelperProject
 */
class Project extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'client_id',
    ];

    protected $appends = ['avatar'];

    public static function query(): Builder|ProjectQueryBuilder
    {
        return parent::query();
    }

    public function newEloquentBuilder($query): ProjectQueryBuilder
    {
        return new ProjectQueryBuilder($query);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tasks(): HasMany|TaskQueryBuilder
    {
        return $this->hasMany(Task::class);
    }

    public function times(): HasMany|TimeQueryBuilder
    {
        return $this->hasMany(Time::class);
    }

    public function activities(): HasMany|ActivityQueryBuilder
    {
        return $this->hasMany(Activity::class);
    }

    public function members(): BelongsToMany|UserQueryBuilder
    {
        return $this->belongsToMany(User::class, 'project_members', 'project_id', 'member_id');
    }

    public function latestActivity(): HasOne|ActivityQueryBuilder
    {
        return $this->hasOne(Activity::class)->latest();
    }

    protected function avatar(): Attribute
    {
        return Attribute::make(
            get: fn() => (new Avatar(config('laravolt.avatar')))->create($this->name)->toBase64(),
        );
    }
}
