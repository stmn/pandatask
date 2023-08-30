<?php

namespace App\Models;

use App\QueryBuilders\UserNotificationQueryBuilder;
use App\QueryBuilders\UserQueryBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property bool $mail
 */
class UserNotification extends Model
{
    public $incrementing = false;
    protected $primaryKey = null;

    protected $fillable = [
        'name',
        'mail',
    ];
    protected $casts = [
        'mail' => 'boolean',
        'extra' => 'array',
    ];

    const UPDATED_AT = null;
    const CREATED_AT = null;

    public static function query(): Builder|UserNotificationQueryBuilder
    {
        return parent::query();
    }

    public function newEloquentBuilder($query): UserNotificationQueryBuilder
    {
        return new UserNotificationQueryBuilder($query);
    }

    // Relations

    public function user(): BelongsToMany|UserQueryBuilder
    {
        return $this->belongsTo(User::class);
    }

    // Actions

    public function via(): array
    {
        $via = [];

        if ($this->mail) {
            $via[] = 'mail';
        }

        return $via;
    }

    // Attributes

}
