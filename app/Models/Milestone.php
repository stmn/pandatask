<?php

namespace App\Models;

use App\QueryBuilders\ProjectQueryBuilder;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperComment
 */
class Milestone extends Model
{
    protected $fillable = [
        'name',
    ];

//    public static function query(): Builder|CommentQueryBuilder
//    {
//        return parent::query();
//    }
//
//    public function newEloquentBuilder($query): CommentQueryBuilder
//    {
//        return new CommentQueryBuilder($query);
//    }

    public function project(): BelongsTo|ProjectQueryBuilder
    {
        return $this->belongsTo(Project::class);
    }
}
