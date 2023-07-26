<?php

namespace App\Models;

use App\Enums\ActivityType;
use App\Enums\ProjectRole;
use App\Models\Traits\useLatestActivity;
use App\QueryBuilders\ActivityQueryBuilder;
use App\QueryBuilders\ProjectQueryBuilder;
use App\QueryBuilders\TaskQueryBuilder;
use App\QueryBuilders\TimeQueryBuilder;
use App\QueryBuilders\UserQueryBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Laravolt\Avatar\Avatar;

/**
 * @mixin IdeHelperProject
 */
class Project extends Model
{
    use SoftDeletes, useLatestActivity;

    protected $fillable = [
        'name',
        'description',
        'client_id',
        'latest_activity_id',
        'latest_activity_at',
        'statuses',
        'priorities',
        'custom_fields',
    ];

    protected $appends = [
        'avatar'
    ];

    protected $casts = [
        'statuses' => 'array',
        'priorities' => 'array',
        'custom_fields' => 'array',
    ];

    protected static function booted(): void
    {
        static::creating(function (Project $project) {
            $project->latest_activity_at = now();
        });
    }

    public static function query(): Builder|ProjectQueryBuilder
    {
        return parent::query();
    }

    public function newEloquentBuilder($query): ProjectQueryBuilder
    {
        return new ProjectQueryBuilder($query);
    }

    public function tasks(): HasMany|TaskQueryBuilder
    {
        return $this->hasMany(Task::class);
    }

    public function times(): HasMany|TimeQueryBuilder
    {
        return $this->hasMany(Time::class);
    }

    public function pages(): HasMany|TimeQueryBuilder
    {
        return $this->hasMany(Page::class);
    }

    public function milestones(): HasMany
    {
        return $this->hasMany(Milestone::class);
    }

    public function activities(): HasMany|ActivityQueryBuilder
    {
        return $this->hasMany(Activity::class);
    }

    public function members(): BelongsToMany|UserQueryBuilder
    {
        return $this->belongsToMany(User::class, 'project_members', 'project_id', 'member_id');
    }

    public function teamMembers(): BelongsToMany|UserQueryBuilder
    {
        return $this->members()->wherePivot('role', ProjectRole::TEAM_MEMBER);
    }

    public function clients(): BelongsToMany|UserQueryBuilder
    {
        return $this->members()->where('role', ProjectRole::CLIENT);
    }

    public function setCustomFieldsAttribute(array $value): void
    {
        $this->attributes['custom_fields'] = json_encode(collect($value)
            ->filter(fn($field) => $field['key'] !== '')
            ->map(fn($field) => [
                'id' => $field['id'] ?? Str::uuid(),
                'key' => $field['key'],
                'label' => $field['label'],
                'type' => $field['type'],
                'options' => $field['options'] ?? [],
            ])
            ->values()
            ->toArray());
    }

    protected function avatar(): Attribute
    {
        return Attribute::make(
            get: function () {
                return url('storage/project-avatars/' . $this->id . '.png');
            },
        );
    }
}
