<?php

namespace App\Models;

use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Model;
use Lorisleiva\LaravelSearchString\Concerns\SearchString;

class Task extends Model
{
    use SearchString, Filterable;

    private static $whiteListFilter = ['*'];

    public function getAliasListFilter(): ?array
    {
        return [
            'custom_fields->cost' => 'cost',
        ];
    }

    protected $searchStringColumns = [
        'subject' => ['searchable' => true],
        'number' => ['searchable' => true],
        'assignees' => ['relationship' => true],
        'custom_fields->cost' => [
            'key' => 'cost',
        ],
        'custom_fields->category' => [
            'key' => 'category',
        ],
    ];

    public function getSearchStringOptions()
    {
        return [
            'columns' => $this->searchStringColumns ?? 'KURWA',
            'keywords' => $this->searchStringKeywords ?? [],
        ];
    }

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

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function assignees()
    {
        return $this->belongsToMany(User::class, 'task_users');
    }

    public function times()
    {
        return $this->hasMany(Time::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasManyThrough(Comment::class, Activity::class);
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    public function latestActivity()
    {
        return $this->hasOne(Activity::class)->latest();
    }

    public function getUrlAttribute()
    {
        return route('project.task', ['project' => $this->project_id, 'task' => $this]);
    }
}
