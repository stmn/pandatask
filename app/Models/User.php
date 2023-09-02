<?php

namespace App\Models;

use App\Models\Traits\useCacheBuilder;
use App\QueryBuilders\GroupQueryBuilder;
use App\QueryBuilders\ProjectQueryBuilder;
use App\QueryBuilders\TimeQueryBuilder;
use App\QueryBuilders\UserNotificationQueryBuilder;
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
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @mixin IdeHelperUser
 * @property array $settings
 * @property string $full_name
 */
class User extends Authenticatable implements HasMedia
{
    use HasFactory, Notifiable, SoftDeletes;//, useCacheBuilder;
    use InteractsWithMedia;

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

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')
//            ->useFallbackUrl('/storage/default-avatars/?chars=2&name=' . urlencode($this->full_name))
//            ->useFallbackPath(storage_path('app/public/user-avatars/' . $this->id . '.png')
            ->singleFile();
    }

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

    public function projects(): BelongsToMany|ProjectQueryBuilder
    {
        return $this->belongsToMany(Project::class, 'project_members', 'member_id');
    }

    public function userNotifications(): HasMany|UserNotificationQueryBuilder
    {
        return $this->hasMany(UserNotification::class);
    }

    // Actions

    public function isAdmin(): bool
    {
        return $this->groups()->where('id', 1)->exists();
    }

    public function isClient(): bool
    {
        return $this->groups()->where('id', 2)->exists();
    }

    // Attributes

    protected function avatar(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->getFirstMediaUrl('avatar');
//                if(!$url){
//                    $image = (new Avatar([
//                        ...config('laravolt.avatar'),
//                        ...['chars' => 2]
//                    ]))
//                        ->create($this->full_name)
//                        ->save(storage_path('app/public/user-avatars/' . $user->id . '.png'));
//                }

//                if (!$this->full_name) {
//                    return null;
//                }
                //                return Cache::rememberForever('avatar-' . $this->full_name, function () {

//                return (new Avatar([
//                    ...config('laravolt.avatar'),
//                    ...['chars' => 2]
//                ]))->setDimension(64,64)->create($this->full_name)->toSvg();

//                return url('storage/user-avatars/' . $this->id . '.png');
//                return Cache::rememberForever('avatar-' . $this->full_name, function () {
//                    return (new Avatar([
//                        ...config('laravolt.avatar'),
//                        ...['chars' => 2]
//                    ]))->create($this->full_name)->toBase64();
//                });
            },
        );
    }

    public function getAvatar()
    {
        $initials = function () {
            $words = explode(' ', $this->full_name);
            $initialsArray = array_map(fn($word) => strtoupper($word[0]), $words);
            return implode('', $initialsArray);
        };

        $color = function () use ($initials) {
            $backgrounds = config('laravolt.avatar.backgrounds');
            return $backgrounds[ord($initials()[0]) % count($backgrounds)];
        };

        return (new Avatar([
            ...config('laravolt.avatar'),
            ...[
                'name' => $this->full_name,
                'chars' => 2,
                'themes' => [],
                'shape' => 'square',
                'backgrounds' => [$color()]
            ]
        ]))
            ->create($this->full_name)
            ->toBase64();
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
