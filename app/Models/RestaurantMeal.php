<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantMeal extends Model
{
    use HasFactory;
    protected $fillable = ['name',
                            'price',
                            'restaurant_id'];

}
