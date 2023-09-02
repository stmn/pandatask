<?php

namespace App\Models;

use App\Enums\ActivityType;
use App\QueryBuilders\ActivityQueryBuilder;
use App\QueryBuilders\CommentQueryBuilder;
use App\QueryBuilders\ProjectQueryBuilder;
use App\QueryBuilders\TaskQueryBuilder;
use App\QueryBuilders\UserQueryBuilder;
use Arr;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @mixin IdeHelperActivity
 */
class Activity extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'user_id',
        'project_id',
        'task_id',
        'comment_id',
        'type',
        'details',
        'private',
    ];

    protected $casts = [
        'type' => ActivityType::class,
        'private' => 'boolean',
        'details' => 'array',
    ];

    protected $appends = [
        'description',
    ];

    protected static function booted(): void
    {
        static::creating(function (Activity $activity) {
            if (!$activity->user_id) {
                $activity->user_id = auth()->id();
            }
        });

        static::created(function (Activity $activity) {
            $latest = [
                'latest_activity_id' => $activity->id,
                'latest_activity_at' => $activity->created_at,
            ];
            $activity->task->update($latest);
            $activity->project->update($latest);
        });
    }

    public static function query(): Builder|ActivityQueryBuilder
    {
        return parent::query();
    }

    public function newEloquentBuilder($query): ActivityQueryBuilder
    {
        return new ActivityQueryBuilder($query);
    }

    public function user(): BelongsTo|UserQueryBuilder
    {
        return $this->belongsTo(User::class);
    }

    public function project(): BelongsTo|ProjectQueryBuilder
    {
        return $this->belongsTo(Project::class);
    }

    public function task(): BelongsTo|TaskQueryBuilder
    {
        return $this->belongsTo(Task::class);
    }

    public function author(): BelongsTo|UserQueryBuilder
    {
        return $this->belongsTo(User::class);
    }

    public function comment(): BelongsTo|CommentQueryBuilder
    {
        return $this->belongsTo(Comment::class);
    }

    public function activity(): MorphTo
    {
        return $this->morphTo();
    }

    protected function description(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->type->getDescription(),
        );
    }

    protected function details(): Attribute
    {
        return Attribute::make(
            get: function ($details) {
                if(is_null($details)){
                    return [];
                }

                $details = json_decode($details, true);

                $collection = collect();

                foreach (Arr::get($details, 'changed', []) as $key => $change) {
                    if ($key === 'priority_id') {
                        $old = Priority::find($change[0]);
                        $new = Priority::find($change[1]);
                        $collection->push([
                            'field' => $key,
                            'old' => $old->only('name', 'color'),
                            'new' => $new->only('name', 'color'),
                        ]);
                    } elseif ($key === 'status_id') {
                        $old = Status::find($change[0]);
                        $new = Status::find($change[1]);
                        $collection->push([
                            'field' => $key,
                            'old' => $old->only('name', 'color'),
                            'new' => $new->only('name', 'color'),
                        ]);
                    } else {
                        $collection->push([
                            'field' => $key,
                            'old' => $change[0],
                            'new' => $change[1],
                        ]);
                    }
                }
                foreach (Arr::get($details, 'attached', []) as $key => $attached) {
                    if ($key === 'assignees') {
                        $users = User::find($attached);
                        $collection->push([
                            'field' => $key,
                            'attached' => $users,
                        ]);
                    }
                }
                foreach (Arr::get($details, 'detached', []) as $key => $attached) {
                    if ($key === 'assignees') {
                        $users = User::find($attached);
                        $collection->push([
                            'field' => $key,
                            'detached' => $users,
                        ]);
                    }
                }

                return $collection;
            },
        );
    }
}
