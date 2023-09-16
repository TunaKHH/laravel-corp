<?php
namespace App\Utils;

class TransactionOrderData
{
    public function __construct(
        public $merchantID,
        public $merchantTradeNo,
        public $merchantTradeDate,
        public $paymentType,
        public $totalAmount,
        public $tradeDesc,
        public $itemName,
        public $choosePayment,
        public $encryptType,
        public $returnURL
    ) {}

    /**
     *  從陣列建立交易訂單資料
     *
     * @param array $data
     * @return TransactionOrderData
     */
    public static function fromArray(array $data): self
    {
        return new self(
            $data['MerchantID'],
            $data['MerchantTradeNo'],
            $data['MerchantTradeDate'],
            $data['PaymentType'],
            $data['TotalAmount'],
            $data['TradeDesc'],
            $data['ItemName'],
            $data['ChoosePayment'],
            $data['EncryptType'],
            $data['ReturnURL']
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
            'MerchantTradeDate' => $this->merchantTradeDate,
            'PaymentType' => $this->paymentType,
            'TotalAmount' => $this->totalAmount,
            'TradeDesc' => $this->tradeDesc,
            'ItemName' => $this->itemName,
            'ChoosePayment' => $this->choosePayment,
            'EncryptType' => $this->encryptType,
            'ReturnURL' => $this->returnURL,
        ];
    }

}
