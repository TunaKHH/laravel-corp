<?php

namespace App\Http\Controllers;

use App\Services\ECPay;
use Illuminate\Http\Request;

class ECPayController extends Controller
{
    public function redirectToECPay()
    {
        $order = [
            'MerchantTradeNo' => 'Test' . time(),
            'MerchantTradeDate' => date('Y/m/d H:i:s'),
            'TotalAmount' => 100,
            'TradeDesc' => '測試交易描述',
            'Items' => [
                [
                    'Name' => '商品名稱',
                    'Price' => 100,
                    'Currency' => '元',
                    'Quantity' => 1,
                    'URL' => 'https://www.ecpay.com.tw/',
                ],
            ],
        ];

        $ecpay = new ECPay;
        $html = $ecpay->createOrder($order);

        return $html;
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
