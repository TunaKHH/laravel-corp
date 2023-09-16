<?php

namespace App\Http\Controllers;

use App\Services\ECPayService;
use App\Services\TransactionService;
use Illuminate\Http\Request;

class ECPayController extends Controller
{
    protected $ecpayService;
    protected $transactionService;
    public function __construct(ECPayService $ecpayService,
        TransactionService $transactionService) {
        $this->ecpayService = $ecpayService;
        $this->transactionService = $transactionService;
    }

    /**
     * 建立系統訂單資訊後導向至金流平台
     *
     * @return string
     */
    public function redirectToECPay()
    {
        $order = $this->ecpayService->generateOrderData();
        // 本系統建立自身金流訂單 記錄訂單資訊, 訂單資訊包含(操作者, 金額, 訂單編號, 訂單狀態, 訂單建立時間, 訂單更新時間)
        $this->transactionService->createTransaction($order);
        // 產生假的訂單資料並導向至金流平台
        return $this->ecpayService->redirectToPaymentGateway($order);
    }

    /**
     * 接收金流平台回傳的訂單資訊
     *
     * @param Request $request
     * @return void
     */
    public function handleECPayCallback(Request $request)
    {
        logger()->info('handleECPayCallback');
        logger()->info(json_encode($request->all(), JSON_OBJECT_AS_ARRAY));
        // 接收金流平台回傳的訂單資訊
        $order = $this->ecpayService->getOrderDataFromCallback($request);
        try {
            // todo 向ecpay驗證訂單資訊
            // $this->ecpayService->validateOrderData($order->merchantID, $order->merchantTradeNo);
            // 更新訂單狀態
            $this->transactionService->updateTransactionStatus($order);
            // 根據訂單資訊取得使用者資訊
            $user = $this->transactionService->getUserFromTransaction($order);
            // 更新使用者儲值金額
            $this->transactionService->updateUserMoney($user, $order);
        } catch (\Throwable $th) {
            logger()->error($th->getMessage());
        }

    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
