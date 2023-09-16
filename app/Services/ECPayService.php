<?php

namespace App\Services;

use App\Utils\ECPayCallbackData;
use App\Utils\TransactionOrderData;
use Ecpay\Sdk\Factories\Factory;
use Ecpay\Sdk\Services\UrlService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ECPayService
{
    protected $factory;
    protected $hashKey;
    protected $hashIV;
    protected $payAPIUrl;
    protected $queryAPIUrl;

    public function __construct()
    {
        $this->hashKey = config('ecpay.hash_key');
        $this->hashIV = config('ecpay.hash_iv');
        $this->payAPIUrl = config('ecpay.pay_api_url');
        $this->queryAPIUrl = config('ecpay.query_api_url');

        $this->factory = new Factory([
            'hashKey' => $this->hashKey,
            'hashIv' => $this->hashIV,
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
     * 呼叫ecpay查詢訂單API驗證付款結果
     * todo 修正post失敗的問題
     * @param
     * @return void
     */
    public function validateOrderData($merchantID, $merchantTradeNo)
    {

        // 組合參數
        $postData = [
            'MerchantID' => $merchantID,
            'MerchantTradeNo' => $merchantTradeNo,
            'TimeStamp' => time(),
        ];

        // 產生檢查碼
        $postData['CheckMacValue'] = $this->generateCheckMacValue($postData);

        // 發送 HTTP POST 請求
        $response = Http::asForm()->post($this->queryAPIUrl, $postData);

        logger()->info('ECPay query api response', [
            'response' => $response->json(),
        ]
        );
        // 檢查 HTTP 狀態碼是否為 200
        if (!$response->successful()) {
            throw new \Exception('ECPay query api error');
        }
        $data = $response->json();
        // 檢查回應碼
        if ($data['TradeStatus'] !== '1') {
            throw new \Exception('ECPay 驗證訂單失敗, 訂單編號: ' . $merchantTradeNo . ' 錯誤訊息: ' . $data['RtnMsg']);
        }
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
        // 建立自動送出表單service
        $autoSubmitFormService = $this->factory->create('AutoSubmitFormWithCmvService');
        // 產生自動送出表單html
        return $autoSubmitFormService->generate($orderData->toArray(), $this->payAPIUrl);
    }

    private function generateCheckMacValue($data)
    {
        ksort($data);
        $string = "HashKey={$this->hashKey}&" . http_build_query($data) . "&HashIV={$this->hashIV}";
        $string = urlencode($string);
        $string = strtolower($string);
        $string = str_replace('%2d', '-', $string);
        $string = str_replace('%5f', '_', $string);
        $string = str_replace('%2e', '.', $string);
        $string = str_replace('%21', '!', $string);
        $string = str_replace('%2a', '*', $string);
        $string = str_replace('%28', '(', $string);
        $string = str_replace('%29', ')', $string);
        $string = hash('sha256', $string);
        $string = strtoupper($string);

        return $string;
    }
}
