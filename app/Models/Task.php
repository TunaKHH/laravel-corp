<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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


}
