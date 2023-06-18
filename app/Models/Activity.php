<?php

namespace App\Models;

use App\Enums\ActivityType;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

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

    public function comment(){
        return $this->hasOne(Comment::class);
    }

    // polymorphic relationship
    public function activity()
    {
        return $this->morphTo();
    }

    public function getDescriptionAttribute()
    {
        return ' INFO ';
//        return $this->type->getDescription();
    }
}
