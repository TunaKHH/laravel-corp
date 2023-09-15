<?php

namespace App\Services;

use App\Utils\ECPayCallbackData;
use App\Utils\TransactionOrderData;
use Ecpay\Sdk\Factories\Factory;
use Ecpay\Sdk\Services\UrlService;
use Illuminate\Http\Request;

class ECPayService
{
    protected $factory;

    public function __construct()
    {
        $this->factory = new Factory([
            'hashKey' => config('ecpay.hash_key'),
            'hashIv' => config('ecpay.hash_iv'),
        ]);
    }

    /**
     * 接收金流平台回傳的訂單資訊
     *
     * @param Request $request
     * @return ECPayCallbackData
     */
    public function getOrderDataFromCallback(Request $request): ECPayCallbackData
    {
        $response = [
            'MerchantID' => $request->input('MerchantID'),
            'MerchantTradeNo' => $request->input('MerchantTradeNo'),
            'PaymentDate' => $request->input('PaymentDate'),
            'PaymentType' => $request->input('PaymentType'),
            'PaymentTypeChargeFee' => $request->input('PaymentTypeChargeFee'),
            'RtnCode' => $request->input('RtnCode'),
            'RtnMsg' => $request->input('RtnMsg'),
            'SimulatePaid' => $request->input('SimulatePaid'),
            'TradeAmt' => $request->input('TradeAmt'),
            'TradeDate' => $request->input('TradeDate'),
            'TradeNo' => $request->input('TradeNo'),
            'CheckMacValue' => $request->input('CheckMacValue'),
        ];

        return ECPayCallbackData::fromArray($response);
    }

    /**
     * 產生ecpay要用的訂單資料
     *
     * @return TransactionOrderData
     */
    public function generateOrderData(): TransactionOrderData
    {
        $input = [
            'MerchantID' => '2000132',
            'MerchantTradeNo' => 'Tuna' . time(),
            'MerchantTradeDate' => date('Y/m/d H:i:s'),
            'PaymentType' => 'aio',
            'TotalAmount' => 996,
            'TradeDesc' => UrlService::ecpayUrlEncode('Tuna系統測試'),
            'ItemName' => '(Tuna系統測試)儲值測試 996 TWD x 1',
            'ChoosePayment' => 'Credit',
            'EncryptType' => 1,
            'ReturnURL' => config('ecpay.redirect_url'),
        ];
        return TransactionOrderData::fromArray($input);
    }

    /**
     * 將訂單資料導向至金流平台
     *
     * @param TransactionOrderData $orderData
     * @return string
     */
    public function redirectToPaymentGateway(TransactionOrderData $orderData)
    {
        $action = config('ecpay.api_url');
        $autoSubmitFormService = $this->factory->create('AutoSubmitFormWithCmvService');

        return $autoSubmitFormService->generate($orderData->toArray(), $action);
    }
}
