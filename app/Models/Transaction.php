<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    /** @var 訂單未付款 */
    const STATUS_PENDING = 0;
    /** @var 訂單已付款 */
    const STATUS_PAID = 1;
    /** @var 訂單已取消 */
    const STATUS_CANCELLED = 2;

    protected $fillable = [
        'user_id',
        'transaction_number',
        'amount',
        'status',
    ];

    /**
     * @var array
     */
    protected $attributes = [
        'status' => self::STATUS_PENDING,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
