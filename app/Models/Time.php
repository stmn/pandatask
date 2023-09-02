<?php

namespace App\Models;

use App\QueryBuilders\TaskQueryBuilder;
use App\QueryBuilders\TimeQueryBuilder;
use App\QueryBuilders\UserQueryBuilder;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperTime
 */
class Time extends Model
{
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

    protected $appends = [
        'permissions',
    ];

    public static function query(): Builder|TimeQueryBuilder
    {
        return parent::query();
    }

    public function newEloquentBuilder($query): TimeQueryBuilder
    {
        return new TimeQueryBuilder($query);
    }

    // Relations

    public function task(): BelongsTo|TaskQueryBuilder
    {
        return $this->belongsTo(Task::class);
    }

    public function author(): BelongsTo|UserQueryBuilder
    {
        return $this->belongsTo(User::class);
    }

    // Actions

    public function stop(): bool
    {
        $minimumTime = 60;

        if ($this->start_at->diffInSeconds(now()) < $minimumTime) {
            $this->delete();

            return false;
        }

        $this->update([
            'end_at' => now(),
        ]);

        $this->author->update([
            'active_time_id' => null
        ]);

        return true;
    }

    protected function permissions(): Attribute
    {
        return Attribute::make(
            get: fn() => [
                'update time' => loggedUser()->can('update', $this),
            ],
        );
    }
}
