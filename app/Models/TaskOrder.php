<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\TaskOrder
 *
 * @property int $id
 * @property int $task_id
 * @property int $user_id
 * @property int $meal_id 餐點id,最後確認時用若要修改可統一更改
 * @property string $meal_name
 * @property int $meal_price
 * @property int $qty
 * @property string|null $remark
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $total_price
 * @property-read \App\Models\RestaurantMeal $restaurantMeal
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\TaskOrderFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|TaskOrder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TaskOrder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TaskOrder query()
 * @method static \Illuminate\Database\Eloquent\Builder|TaskOrder whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaskOrder whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaskOrder whereMealId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaskOrder whereMealName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaskOrder whereMealPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaskOrder whereQty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaskOrder whereRemark($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaskOrder whereTaskId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaskOrder whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaskOrder whereUserId($value)
 * @mixin \Eloquent
 */
class TaskOrder extends Model
{
    use HasFactory;
    protected $fillable = [
        'restaurant_id',
        'user_id',
        'task_id',
        'qty',
    ];

    protected $attributes = [
        'qty' => 1,
    ];

    public function restaurantMeal(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(RestaurantMeal::class);
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getTotalPriceAttribute()
    {
        return $this->qty * $this->meal_price;
    }

}
