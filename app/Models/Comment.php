<?php

namespace App\Models;

use App\QueryBuilders\CommentQueryBuilder;
use App\QueryBuilders\TaskQueryBuilder;
use App\QueryBuilders\UserQueryBuilder;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperComment
 */
class Comment extends Model
{
    use Cachable;

    protected $cacheCooldownSeconds = 300;

    protected $fillable = [
        'author_id',
        'content',
        'private'
    ];

    const CREATED_AT = null;
    const UPDATED_AT = null;

    public static function query(): Builder|CommentQueryBuilder
    {
        return parent::query();
    }

    public function newEloquentBuilder($query): CommentQueryBuilder
    {
        return new CommentQueryBuilder($query);
    }

    public function task(): BelongsTo|TaskQueryBuilder
    {
        return $this->belongsTo(Task::class);
    }

    public function author(): BelongsTo|UserQueryBuilder
    {
        return $this->belongsTo(User::class);
    }
}
