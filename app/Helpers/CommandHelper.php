<?php

namespace App\Helpers;

class CommandHelper
{
    const GET_MY_LINE_ID_COMMANDS = ['取得我的lineId'];
    const OPEN_ORDER_COMMANDS = ['!{', '!渴了', '!餓了', '!吃飯', '點餐', '開始點餐', '開啟點餐'];
    const CLOSE_ORDER_COMMANDS = ['!}', '關閉點餐', '結束點餐'];
    const CHECK_BALANCE_COMMANDS = ['!$'];
    const CONFIRM_COMMANDS = ['!!', '確認', '確認餐點', '確認點餐', '查看', '查看餐點', '查看點餐'];

    private $normalizedCommand;
    function __construct($normalizedCommand)
    {
        $this->normalizedCommand = $normalizedCommand;
    }

    public function matches($commandsArray): bool
    {
        return $this->isACommand($this->normalizedCommand, $commandsArray);
    }

    /**
     * 判斷是否為對應指令
     * @param string $input
     * @param array $commandsArray
     * @return boolean
     */
    public function isACommand($input, $commandsArray): bool
    {
        return in_array($this->normalizeCommand($input), $commandsArray);
    }

    public function normalizeCommand($command)
    {
        // 這裡我假設 nf_to_wf 是一個存在的函數
        // 如果不是，請替換為正確的函數來轉換全形和半形字符
        return nf_to_wf($command, 'wf_to_nf');
    }

    public function isDelInfo()
    {
        $inarr = explode(" ", $this->normalizedCommand);
        return in_array($inarr[0], ['刪除', '刪除餐點']);
    }

    public function isOrderInfo()
    {
        $inarr = explode(" ", $this->normalizedCommand);
        $meal_price = $inarr[1] ?? null;
        if (isset($meal_price)) {
            return substr($meal_price, 0, 1) === '$';
        }
        return false;
    }
}
