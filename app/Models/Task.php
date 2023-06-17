<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
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
        return $this->hasMany(Comment::class);
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
