<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
