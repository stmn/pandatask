<?php

namespace App\Models;

use App\QueryBuilders\GroupQueryBuilder;
use App\QueryBuilders\TimeQueryBuilder;
use App\QueryBuilders\UserQueryBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use Laravolt\Avatar\Avatar;

/**
 * @mixin IdeHelperUser
 * @property array $settings
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'active_time_id',
        'first_name',
        'last_name',
        'email',
        'public_email',
        'password',
        'job_title',
        'phone',
        'settings',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'settings' => 'array',
    ];

    protected $appends = [
        'avatar',
        'full_name',
    ];

    public static function query(): Builder|UserQueryBuilder
    {
        return parent::query();
    }

    public function newEloquentBuilder($query): UserQueryBuilder
    {
        return new UserQueryBuilder($query);
    }

    // Relations

    public function times(): HasMany|TimeQueryBuilder
    {
        return $this->hasMany(Time::class, 'author_id');
    }

    public function groups(): BelongsToMany|GroupQueryBuilder
    {
        return $this->belongsToMany(Group::class, 'group_users');
    }

    public function activeTime(): BelongsTo|TimeQueryBuilder
    {
        return $this->belongsTo(Time::class, 'active_time_id');
    }

    // Actions

    public function isAdmin(): bool
    {
        return $this->groups()->where('id', 1)->exists();
    }

    // Attributes

    protected function avatar(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (!$this->full_name) {
                    return null;
                }

                return Cache::rememberForever('avatar-' . $this->full_name, function () {
                    return (new Avatar([
                        ...config('laravolt.avatar'),
                        ...['chars' => 2]
                    ]))->create($this->full_name)->toBase64();
                });
            },
        );
    }

    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn() => trim($this->first_name . ' ' . $this->last_name)
        );
    }

    protected function settings(): Attribute
    {
        return Attribute::make(
            get: fn($value) => array_merge([
                'dashboard_projects' => 10,
                'dashboard_tasks' => 5,
            ], json_decode($value ?? '[]', true))
        );
    }

    protected function getCanAttribute(): array
    {
        return [];
    }
}
