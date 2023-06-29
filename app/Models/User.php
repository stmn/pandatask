<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\QueryBuilders\GroupQueryBuilder;
use App\QueryBuilders\TimeQueryBuilder;
use App\QueryBuilders\UserQueryBuilder;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Scout\Searchable;
use Laravolt\Avatar\Avatar;
use Rennokki\QueryCache\Traits\QueryCacheable;

//use Lorisleiva\LaravelSearchString\Concerns\SearchString;

/**
 * @mixin IdeHelperUser
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'public_email',
        'password',
        'job_title',
        'phone',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected $appends = [
        'avatar',
        'full_name',
//        'active_time',
    ];

    public static function query(): Builder|UserQueryBuilder
    {
        return parent::query();
    }

    public function newEloquentBuilder($query): UserQueryBuilder
    {
        return new UserQueryBuilder($query);
    }

    public function times(): HasMany|TimeQueryBuilder
    {
        return $this->hasMany(Time::class, 'author_id');
    }

    public function groups(): BelongsToMany|GroupQueryBuilder
    {
        return $this->belongsToMany(Group::class, 'group_users');
    }

    public function isAdmin(): bool
    {
        return $this->groups()->where('id', 1)->exists();
    }

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

    protected function activeTime(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->times()
                ->with('task.project')
                ->whereNull('end_at')
                ->first(),
        );
    }

    protected function getCanAttribute()
    {
        return [];
    }
}
