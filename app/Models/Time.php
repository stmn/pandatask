<?php

namespace App\Models;

use App\QueryBuilders\TaskQueryBuilder;
use App\QueryBuilders\UserQueryBuilder;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Rennokki\QueryCache\Traits\QueryCacheable;

/**
 * @mixin IdeHelperTime
 */
class Time extends Model
{
    use Cachable;
    protected $cacheCooldownSeconds = 300;

    protected $fillable = [
        'project_id',
        'task_id',
        'author_id',
        'start_at',
        'end_at',
        'time',
        'comment',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
    ];

    public static function query(): Builder|TaskQueryBuilder
    {
        return parent::query();
    }

    public function newEloquentBuilder($query): TaskQueryBuilder
    {
        return new TaskQueryBuilder($query);
    }

    public function task(): BelongsTo|TaskQueryBuilder
    {
        return $this->belongsTo(Task::class);
    }

    public function author(): BelongsTo|UserQueryBuilder
    {
        return $this->belongsTo(User::class);
    }
}
