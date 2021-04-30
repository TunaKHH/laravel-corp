<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
            ->select('meal_name', 'meal_price', 'remark', DB::raw('SUM(qty) as qty_sum'))
            ->where('task_id',$this->id)
            ->groupBy('meal_name', 'meal_price','remark')
            ->get();
    }




}
