<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\RestaurantPhoto
 *
 * @property int $id
 * @property string $url
 * @property int|null $restaurant_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\RestaurantPhotoFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|RestaurantPhoto newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RestaurantPhoto newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RestaurantPhoto query()
 * @method static \Illuminate\Database\Eloquent\Builder|RestaurantPhoto whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RestaurantPhoto whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RestaurantPhoto whereRestaurantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RestaurantPhoto whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RestaurantPhoto whereUrl($value)
 * @mixin \Eloquent
 */
class RestaurantPhoto extends Model
{
    use HasFactory;

    /**
     * @var mixed
     */
    private $restaurant_id;
    /**
     * @var mixed|string
     */
    private $url;
}
