<?php

namespace App\Http\Controllers;

use App\Services\ECPayService;
use Illuminate\Http\Request;

class ECPayController extends Controller
{
    protected $ecpayService;
    public function __construct(ECPayService $ecpayService)
    {
        $this->ecpayService = $ecpayService;
    }

    /**
     * 建立系統訂單資訊後導向至金流平台
     *
     * @return string
     */
    public function redirectToECPay()
    {
        // 本系統建立自身金流訂單 記錄訂單資訊, 訂單資訊包含(操作者, 金額, 訂單編號, 訂單狀態, 訂單建立時間, 訂單更新時間)

        // 產生假的訂單資料並導向至金流平台
        return $this->ecpayService->redirectToPaymentGateway($this->ecpayService->generateOrderData());
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
