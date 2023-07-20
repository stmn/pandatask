<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperTask
 */
class Setting extends Model
{
    protected $fillable = [
        'name',
        'value',
    ];
}
