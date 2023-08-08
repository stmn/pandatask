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

    public $incrementing = false;

    protected $primaryKey = 'name';

    const CREATED_AT = null;
    const UPDATED_AT = null;

    public static function getData(): array
    {
        return self::query()
            ->select(['value', 'name'])
            ->get()
            ->pluck('value', 'name')
            ->toArray();
    }
}
