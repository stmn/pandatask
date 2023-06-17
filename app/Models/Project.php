<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravolt\Avatar\Avatar;

class Project extends Model
{
    protected $fillable = [
        'name',
        'description',
        'client_id',
    ];

    protected $appends = ['avatar'];

    public function client()
    {
        return $this->belongsTo(User::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function times()
    {
        return $this->hasMany(Time::class);
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'project_members', 'project_id', 'member_id');
    }

    public function latestActivity()
    {
        return $this->hasOne(Activity::class)->latest();
    }

    public function getAvatarAttribute()
    {
        return (new Avatar(config('laravolt.avatar')))->create($this->name)->toBase64();
    }

}
