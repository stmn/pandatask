<?php

namespace App\Models;

use App\Enums\ActivityType;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = [
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

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // polymorphic relationship
    public function activity()
    {
        return $this->morphTo();
    }

    public function getDescriptionAttribute()
    {
        return $this->type->getDescription();
    }
}
