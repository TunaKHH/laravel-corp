<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name 名字
 * @property string $account 帳號
 * @property string $password
 * @property int $deposit 剩餘金額
 * @property string|null $line_id
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAccount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDeposit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLineId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'line_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function moneyRecords()
    {
        return $this->hasMany(MoneyRecords::class);
    }

    public static function getUserByLineId($lineId)
    {
        return User::where('line_id', $lineId)
            ->first();
    }

    // 取得所有人的餘額
    public static function getAllDeposit()
    {
        return DB::table('users')
            ->select('name', 'deposit')
            ->orderBy('deposit', 'DESC')
            ->get();
    }

    // 扣這個人的錢
    public function reduceMoney($amount, $remark = '', $operator_id = false)
    {
        // 寫紀錄
        $moneyRecord = new MoneyRecords;
        $moneyRecord->user_id = $this->id;
        $moneyRecord->amount = $amount * -1;
        $moneyRecord->remark = $remark;
        $moneyRecord->operator_id = $operator_id ?? $this->id;
        $moneyRecord->save();

        // 真正扣錢
        $this->deposit = $this->deposit - $amount;
        return $this->save();
    }

    // 加這個人的錢
    public function addMoney($amount, $remark = '', $operator_id = false)
    {
        // 寫紀錄
        $moneyRecord = new MoneyRecords;
        $moneyRecord->user_id = $this->id;
        $moneyRecord->amount = $amount;
        $moneyRecord->remark = $remark;
        $moneyRecord->operator_id = $operator_id ?? $this->id;
        $moneyRecord->save();

        // 真正加錢
        $this->deposit = $this->deposit + $amount;
        return $this->save();
    }

}
