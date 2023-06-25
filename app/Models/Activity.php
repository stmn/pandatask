<?php

namespace App\Models;

use App\Enums\ActivityType;
use App\QueryBuilders\ActivityQueryBuilder;
use App\QueryBuilders\CommentQueryBuilder;
use App\QueryBuilders\ProjectQueryBuilder;
use App\QueryBuilders\TaskQueryBuilder;
use App\QueryBuilders\UserQueryBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
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
        'type',
        'activity_type',
        'activity_id',
        'private',
    ];

    protected $casts = [
        'type' => ActivityType::class,
        'private' => 'boolean',
    ];

    protected $appends = [
        'description',
    ];

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

    public function comments(): HasMany|CommentQueryBuilder
    {
        return $this->hasMany(Comment::class);
    }

    public function comment(): HasOne|CommentQueryBuilder
    {
        return $this->hasOne(Comment::class);
    }

    public function activity(): MorphTo
    {
        return $this->morphTo();
    }

    protected function description(): Attribute
    {
        return Attribute::make(
            get: fn() => ' [activity_info] ',
        );
    }
}
