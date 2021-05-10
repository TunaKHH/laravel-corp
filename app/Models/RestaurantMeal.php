<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\RestaurantMeal
 *
 * @property int $id
 * @property string $name 餐點名稱
 * @property int $price 餐點金額
 * @property int $restaurant_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\RestaurantMealFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|RestaurantMeal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RestaurantMeal newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RestaurantMeal query()
 * @method static \Illuminate\Database\Eloquent\Builder|RestaurantMeal whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RestaurantMeal whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RestaurantMeal whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RestaurantMeal wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RestaurantMeal whereRestaurantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RestaurantMeal whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class RestaurantMeal extends Model
{
    use HasFactory;
    protected $fillable = ['name',
                            'price',
                            'restaurant_id'];

}
