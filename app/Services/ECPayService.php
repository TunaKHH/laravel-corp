<?php

namespace App\Services;

use Ecpay\Sdk\Factories\Factory;
use Ecpay\Sdk\Services\UrlService;

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
     * 產生ecpay要用的訂單資料
     *
     * @return array
     */
    public function generateOrderData()
    {
        $input = [
            'MerchantID' => '2000132',
            'MerchantTradeNo' => 'Test' . time(),
            'MerchantTradeDate' => date('Y/m/d H:i:s'),
            'PaymentType' => 'aio',
            'TotalAmount' => 996,
            'TradeDesc' => UrlService::ecpayUrlEncode('Tuna系統測試'),
            'ItemName' => '(Tuna系統測試)儲值測試 996 TWD x 1',
            'ChoosePayment' => 'Credit',
            'EncryptType' => 1,
            'ReturnURL' => config('ecpay.redirect_url'),
        ];
        return $input;
    }

    /**
     * 將訂單資料導向至金流平台
     *
     * @param array $orderData
     * @return string
     */
    public function redirectToPaymentGateway(array $orderData)
    {
        $action = config('ecpay.api_url');
        $autoSubmitFormService = $this->factory->create('AutoSubmitFormWithCmvService');

        return $autoSubmitFormService->generate($orderData, $action);
    }
}
