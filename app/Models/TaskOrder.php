<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskOrder extends Model
{
    use HasFactory;
    protected $fillable = [
        'restaurant_id',
        'user_id',
        'task_id',
        'qty',
    ];

    public function restaurantMeal(){
        return $this->belongsTo(RestaurantMeal::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
