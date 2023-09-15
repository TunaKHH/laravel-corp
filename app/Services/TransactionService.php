<?php
namespace App\Services;

use App\Models\Transaction;
use App\Models\User;
use App\Utils\ECPayCallbackData;
use App\Utils\TransactionOrderData;
use Auth;

class TransactionService
{
    /**
     * 建立系統訂單資訊
     */
    public function createTransaction(TransactionOrderData $order)
    {
        // 本系統建立自身金流訂單 記錄訂單資訊, 訂單資訊包含(操作者, 金額, 訂單編號, 訂單狀態, 訂單建立時間, 訂單更新時間)
        $transaction = new Transaction();
        $transaction->user_id = Auth::id();
        $transaction->amount = $order->totalAmount;
        $transaction->transaction_number = $order->merchantTradeNo;
        $transaction->status = Transaction::STATUS_PENDING;
        $transaction->created_at = now();
        $transaction->updated_at = now();
        $transaction->save();
    }

    /**
     * 更新訂單狀態
     * @param ECPayCallbackData $order
     * @return void
     */
    public function updateTransactionStatus(ECPayCallbackData $order)
    {
        // 更新訂單狀態
        $transaction = Transaction::where('transaction_number', $order->merchantTradeNo)->first();
        $transaction->status = Transaction::STATUS_PAID;
        $transaction->updated_at = now();
        $transaction->save();
    }

    /**
     * 根據訂單資訊取得使用者資訊
     * @param ECPayCallbackData $order
     * @return User
     */
    public function getUserFromTransaction(ECPayCallbackData $order): User
    {
        // 根據訂單資訊取得使用者資訊
        $transaction = Transaction::where('transaction_number', $order->merchantTradeNo)->first();
        return $transaction->user;
    }

    /**
     * 更新使用者儲值金額
     * @param User $user
     * @param ECPayCallbackData $order
     * @return void
     */
    public function updateUserMoney(User $user, ECPayCallbackData $order)
    {
        // 寫紀錄
        $remark = 'ECPay 儲值, 訂單編號: ' . $order->merchantTradeNo . ' 金額: ' . $order->tradeAmt . '元';
        // 更新使用者儲值金額
        $user->addMoneyAndRecord($order->tradeAmt, $remark, $user->id);
    }

}
