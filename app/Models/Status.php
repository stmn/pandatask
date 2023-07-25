<?php

namespace App\Models;

use App\QueryBuilders\StatusQueryBuilder;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\EloquentSortable\SortableTrait;

/**
 * @mixin IdeHelperStatus
 * @property int $order_number
 */
class Status extends Model
{
    use SoftDeletes,
        SortableTrait;

    protected $fillable = [
        'name',
        'order_number',
    ];

    public const CREATED_AT = null;
    public const UPDATED_AT = null;

    public static function query(): Builder|StatusQueryBuilder
    {
        return parent::query();
    }

    public function newEloquentBuilder($query): StatusQueryBuilder
    {
        return new StatusQueryBuilder($query);
    }

    public function tasks(): HasMany|StatusQueryBuilder
    {
        return $this->hasMany(Task::class);
    }

    public function moveTo(int $newPos): void
    {
        $oldPos = $this->order_number;
        $this->update(['order_number' => $newPos]);

        $query = Status::query()->where('id', '!=', $this->id);
        if ($oldPos < $newPos) {
            $query->where('order_number', '>', $oldPos)
                ->where('order_number', '<=', $newPos)
                ->decrement('order_number');
        } else {
            $query->where('order_number', '<', $oldPos)
                ->where('order_number', '>=', $newPos)
                ->increment('order_number');
        }
    }
}
