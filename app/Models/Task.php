<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * App\Models\Task
 *
 * @property int $id
 * @property int $restaurant_id
 * @property string|null $remark 備註
 * @property int $is_open
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read bool $can_order
 * @property-read \App\Models\Restaurant $restaurant
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\TaskOrder[] $taskOrder
 * @property-read int|null $task_order_count
 * @method static \Illuminate\Database\Eloquent\Builder|Task newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Task newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Task query()
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereIsOpen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereRemark($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereRestaurantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'restaurant_id',
        'remark',
    ];

    protected $guarded = [
        '_token',
    ];

    public function restaurant(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function taskOrder(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TaskOrder::class);
    }

    public function getCanOrderAttribute(): bool
    {
        return $this->is_open === 1 ?? false;
    }

    public function getTaskTotals()
    {

        return DB::table('task_orders')
            ->select('meal_id', 'meal_name', 'meal_price', 'remark', DB::raw('SUM(qty) as qty_sum'))
            ->where('task_id',$this->id)
            ->groupBy('meal_id', 'meal_name', 'meal_price','remark')
            ->get();
    }




}
