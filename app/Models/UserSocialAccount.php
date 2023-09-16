<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSocialAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'provider',
        'provider_id',
        'token',
        'refresh_token',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 透過 line id 取得 user
    public static function getUserByLineId($lineId): ?User
    {
        $lineAccount = UserSocialAccount::where('provider', 'line')
            ->where('provider_id', $lineId)
            ->first();
        return $lineAccount ? $lineAccount->user : null;
    }

}
