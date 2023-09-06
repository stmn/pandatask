<?php

namespace App\Models;

use App\Enums\ActivityType;
use App\Models\Traits\useLatestActivity;
use App\QueryBuilders\ActivityQueryBuilder;
use App\QueryBuilders\ProjectQueryBuilder;
use App\QueryBuilders\StatusQueryBuilder;
use App\QueryBuilders\TaskQueryBuilder;
use App\QueryBuilders\TimeQueryBuilder;
use App\QueryBuilders\UserQueryBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @mixin IdeHelperTask
 */
class Task extends Model
{
    use SoftDeletes, useLatestActivity;

    protected $fillable = [
        'subject',
        'description',
        'author_id',
        'number',
        'private',
        'billable',
        'priority_id',
        'status_id',
        'latest_activity_id',
        'latest_activity_at',
        'tags',
        'milestone_id',
        'start_date',
        'end_date',
        'custom_fields',
    ];

    protected $appends = [
        'url',
        'permissions',
    ];

    protected $casts = [
        'private' => 'boolean',
        'billable' => 'boolean',
        'tags' => 'array',
        'start_date' => 'date:Y-m-d',
        'end_date' => 'date:Y-m-d',
        'custom_fields' => 'array',
    ];

    protected static function booted(): void
    {
        static::creating(function (Task $task) {
            if (!$task->author_id) {
                $task->author_id = auth()->id();
            }
            $task->number = intval(Task::whereProjectId($task->project_id)
                    ->orderBy('number', 'desc')
                    ->first()?->number) + 1;
        });

        static::created(function (Task $task) {
            $task->activities()->create([
                'created_at' => $task->created_at,
                'user_id' => $task->author_id,
                'project_id' => $task->project_id,
                'type' => ActivityType::TASK_CREATED,
            ]);
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

    public function activities(): HasMany|ActivityQueryBuilder
    {
        return $this->hasMany(Activity::class);
    }

    public function status(): BelongsTo|StatusQueryBuilder
    {
        return $this->belongsTo(Status::class);
    }

    public function priority(): BelongsTo|ProjectQueryBuilder
    {
        return $this->belongsTo(Priority::class);
    }

    protected function url(): Attribute
    {
        return Attribute::make(
            get: fn() => route('project.task', ['project' => $this->project_id, 'task' => $this])
        );
    }

    protected function totalLoggedTime(): Attribute
    {
        return Attribute::make(
            get: fn() => secondsToTime($this->times()->sum('time'))
        );
    }

    protected function yourLoggedTime(): Attribute
    {
        return Attribute::make(
            get: fn() => secondsToTime($this->times()->where('author_id', loggedUser()?->id)->sum('time'))
        );
    }

    protected function permissions(): Attribute
    {
        return Attribute::make(
            get: fn() => [
                'delete task' => true,//loggedUser()?->can('delete', $this),
            ],
        );
    }

}
