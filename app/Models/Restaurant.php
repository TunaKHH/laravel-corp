<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Restaurant
 *
 * @property int $id
 * @property string|null $name 名字
 * @property string|null $remark 備註
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\RestaurantPhoto[] $photos
 * @property-read int|null $photos_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\RestaurantMeal[] $restaurantMeals
 * @property-read int|null $restaurant_meals_count
 * @method static \Database\Factories\RestaurantFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Restaurant newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Restaurant newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Restaurant query()
 * @method static \Illuminate\Database\Eloquent\Builder|Restaurant whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Restaurant whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Restaurant whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Restaurant whereRemark($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Restaurant whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Restaurant extends Model
{
    use HasFactory;
    protected $attributes = array();

    protected $guarded = [
        '_token',
    ];

    public function photos(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(RestaurantPhoto::class);
    }
    public function restaurantMeals()
    {
        return $this->hasMany(RestaurantMeal::class);
    }

}
