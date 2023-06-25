<?php

namespace App\Models;

use App\QueryBuilders\ActivityQueryBuilder;
use App\QueryBuilders\CommentQueryBuilder;
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
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @mixin IdeHelperTask
 */
class Task extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'subject',
        'description',
        'author_id',
        'number',
        'private',
    ];

    protected $appends = [
        'url',
    ];

    protected static function booted(): void
    {
        static::creating(function (Task $task) {
            $task->number = intval(Task::whereProjectId($task->project_id)
                    ->orderBy('number', 'desc')
                    ->first()?->number) + 1;
        });
    }

    public static function query(): Builder|TaskQueryBuilder
    {
        return parent::query();
    }

    public function newEloquentBuilder($query): TaskQueryBuilder
    {
        return new TaskQueryBuilder($query);
    }

    public function project(): BelongsTo|ProjectQueryBuilder
    {
        return $this->belongsTo(Project::class);
    }

    public function assignees(): BelongsToMany|UserQueryBuilder
    {
        return $this->belongsToMany(User::class, 'task_users');
    }

    public function times(): HasMany|TimeQueryBuilder
    {
        return $this->hasMany(Time::class);
    }

    public function author(): BelongsTo|UserQueryBuilder
    {
        return $this->belongsTo(User::class);
    }

    public function comments(): HasManyThrough|CommentQueryBuilder
    {
        return $this->hasManyThrough(Comment::class, Activity::class);
    }

    public function activities(): HasMany|ActivityQueryBuilder
    {
        return $this->hasMany(Activity::class);
    }

    public function latestActivity(): HasOne|ActivityQueryBuilder
    {
        return $this->hasOne(Activity::class)->latest();
    }

    protected function url(): Attribute
    {
        return Attribute::make(
            get: fn() => route('project.task', ['project' => $this->project_id, 'task' => $this])
        );
    }
}
