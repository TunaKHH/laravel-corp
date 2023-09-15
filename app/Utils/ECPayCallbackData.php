<?php
namespace App\Utils;

class ECPayCallbackData
{
    public function __construct(
        public $merchantID,
        public $merchantTradeNo,
        public $paymentDate,
        public $paymentType,
        public $paymentTypeChargeFee,
        public $rtnCode,
        public $rtnMsg,
        public $simulatePaid,
        public $tradeAmt,
        public $tradeDate,
        public $tradeNo,
        public $checkMacValue
    ) {}

    /**
     *  從陣列建立交易訂單資料
     *
     * @param array $data
     * @return ECPayCallbackData
     */
    public static function fromArray(array $data): self
    {
        return new self(
            $data['MerchantID'],
            $data['MerchantTradeNo'],
            $data['PaymentDate'],
            $data['PaymentType'],
            $data['PaymentTypeChargeFee'],
            $data['RtnCode'],
            $data['RtnMsg'],
            $data['SimulatePaid'],
            $data['TradeAmt'],
            $data['TradeDate'],
            $data['TradeNo'],
            $data['CheckMacValue']
        );
    }

    /**
     *  將交易訂單資料轉換為陣列
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'MerchantID' => $this->merchantID,
            'MerchantTradeNo' => $this->merchantTradeNo,
            'PaymentDate' => $this->paymentDate,
            'PaymentType' => $this->paymentType,
            'PaymentTypeChargeFee' => $this->paymentTypeChargeFee,
            'RtnCode' => $this->rtnCode,
            'RtnMsg' => $this->rtnMsg,
            'SimulatePaid' => $this->simulatePaid,
            'TradeAmt' => $this->tradeAmt,
            'TradeDate' => $this->tradeDate,
            'TradeNo' => $this->tradeNo,
            'CheckMacValue' => $this->checkMacValue,
        ];
    }

}
