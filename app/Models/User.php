<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\QueryBuilders\UserQueryBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Scout\Searchable;
use Laravolt\Avatar\Avatar;

//use Lorisleiva\LaravelSearchString\Concerns\SearchString;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, Searchable;

//    use SearchString;

    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
        ];
    }

    protected $searchStringColumns = [
        'id' => ['searchable' => true],
        'first_name' => ['searchable' => true],
        'last_name' => ['searchable' => true],
//        'number' => ['searchable' => true],
//        'assignees' => ['searchable' => true],
    ];

    public function getSearchStringOptions()
    {
        return [
            'columns' => $this->searchStringColumns ?? 'KURWA',
            'keywords' => $this->searchStringKeywords ?? [],
        ];
    }

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
        'active_time',
    ];

    public static function query(): Builder|UserQueryBuilder
    {
        return parent::query();
    }

    public function newEloquentBuilder($query): UserQueryBuilder
    {
        return new UserQueryBuilder($query);
    }

    public function getAvatarAttribute()
    {
        if (!$this->full_name) {
            return null;
        }

        return (new Avatar([
            ...config('laravolt.avatar'),
            ...['chars' => 2]
        ]))->create($this->full_name)->toBase64();
    }

    public function getFullNameAttribute()
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    public function times()
    {
        return $this->hasMany(Time::class, 'author_id');
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'group_users');
    }

    public function getActiveTimeAttribute()
    {
        return $this->times()
            ->with('task.project')
            ->whereNull('end_at')
            ->first();
    }

    public function isAdmin(): bool
    {
        return $this->groups()->where('id', 1)->exists();
    }
}
