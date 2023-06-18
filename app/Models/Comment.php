<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'author_id',
        'content',
        'private'
    ];

    const CREATED_AT = null;
    const UPDATED_AT = null;


    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class);
    }
}
