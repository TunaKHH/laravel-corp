<?php

namespace App\Services;

use App\Models\Task;
use App\Models\TaskOrder;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class LineService
{
    private $userLineId;
    // 取得我的lineId指令
    const GET_MY_LINE_ID_COMMANDS = ['取得我的lineId'];
    // 開啟點餐指令
    const OPEN_ORDER_COMMANDS = ['!{', '!渴了', '!餓了', '!吃飯', '點餐', '開始點餐', '開啟點餐'];
    // 關閉點餐指令
    const CLOSE_ORDER_COMMANDS = ['!}', '關閉點餐', '結束點餐'];
    // 查看帳號餘額指令
    const CHECK_BALANCE_COMMANDS = ['!$'];
    // 查看餐點指令
    const CONFIRM_COMMANDS = ['!!', '確認', '確認餐點', '確認點餐', '查看', '查看餐點', '查看點餐'];
    // 刪除餐點指令
    const DEL_COMMANDS = ['刪除', '刪除餐點'];
    // 換行符號
    const WRAP_STR = "\n";

    public function handleCommands($command, $groupId)
    {
        // 取得最新的訂單
        $last_task = Task::getLast($groupId);
        // 判斷文字指令是否為點餐
        $is_order = $this->isOrderInfo($command);
        // 判斷文字指令是否為刪除
        $is_del = $this->isDelInfoBy($command);
        $command = nf_to_wf($command, 'wf_to_nf');
        if (in_array($command, self::CONFIRM_COMMANDS)) {
            return $this->confirmOrder($last_task);
        } elseif (in_array($command, self::CHECK_BALANCE_COMMANDS)) {
            return $this->checkBalance();
        } elseif (in_array($command, self::OPEN_ORDER_COMMANDS)) {
            return $this->openOrder($last_task, $groupId);
        } elseif (in_array($command, self::CLOSE_ORDER_COMMANDS)) {
            return $this->closeOrder($last_task);
        } elseif (in_array($command, self::DEL_COMMANDS)) {
            return $this->delOrder($command);
        } elseif (in_array($command, self::GET_MY_LINE_ID_COMMANDS)) {
            return $this->getMyLineId();
        } elseif ($is_order) {
            return $this->orderMeal($command, $last_task, $groupId);
        } elseif ($is_del) {
            return $this->delOrder($command);
        }
    }

    /* 確認點餐
     * @param Task $last_task
     * @return string
     */
    private function confirmOrder(Task $last_task)
    {
        $order_list = $last_task->taskOrder()->get();
        if ($order_list->isEmpty()) {
            return '尚無點餐';
        }

        $info_list = $this->formatOrderList($order_list);
        $money = $this->calculateTotalMoney($order_list);
        $order_processed = $this->processOrderGroupByFood($order_list);

        $res = '訂單建立時間:' . self::WRAP_STR . $last_task->create_time . self::WRAP_STR;
        $res .= $info_list . self::WRAP_STR . '----訂單整理----' . self::WRAP_STR;
        $res .= $order_processed . self::WRAP_STR . '總金額：' . $money;

        return $res;
    }

    private function formatOrderList($order_list)
    {
        $info_list = '';
        foreach ($order_list as $info) {
            $oneLine = [
                $info->id,
                $info->user->nickname ?? $info->user->name,
                $info->meal_name,
                $info->meal_price,
                '數量:' . $info->qty,
                $info->remark,
            ];
            $info_list .= implode(' ', $oneLine) . self::WRAP_STR;
        }
        return $info_list;
    }

    /**
     * 計算總金額
     *
     * @param [type] $order_list
     * @return int|float
     */
    private function calculateTotalMoney($order_list)
    {
        $money = 0;
        foreach ($order_list as $info) {
            $info_money = $info['meal_price'] * $info['qty'];
            $money += $info_money;
        }
        return $money;
    }

    /**
     * 處理訂單
     *
     * @param [type] $order_list
     * @return string
     */
    private function processOrderGroupByFood($order_list)
    {
        $groupby_foodname = [];
        foreach ($order_list as $info) {
            $meal_name = $info['meal_name'];
            $groupby_foodname[$meal_name] = ($groupby_foodname[$meal_name] ?? 0) + $info->qty;
        }
        ksort($groupby_foodname);

        $order_processed = '';
        foreach ($groupby_foodname as $name => $count) {
            $order_processed .= $name . ':' . $count . self::WRAP_STR;
        }
        return $order_processed;
    }

    /* 開啟點餐
     * @param Task $last_task
     * @param int $groupId
     * @return string
     */
    private function openOrder(Task $last_task, $groupId)
    {
        // 判斷是否有開啟的訂單
        if ($last_task->isEmpty() &&
            $last_task->is_open === 1) {
            return '已有開啟的訂單,請直接點餐(EX:鮪 炒飯 $100 不要蔥)';
        }
        // 開啟訂單團
        $task = new Task;
        $task->line_group_id = $groupId;
        return $task->save() ? '好,開啟了,請直接點餐(EX:鮪 炒飯 $100 不要蔥)' : '開啟訂單失敗';
    }

    private function checkBalance()
    {
        $balance_list = User::getAllDeposit();
        $res = '帳號餘額' . self::WRAP_STR;
        $res .= '------------' . self::WRAP_STR;

        $user_str = "";
        foreach ($balance_list as $user) {
            $user_str .= $user['name'] . ':' . $user['balance'] . self::WRAP_STR;
        }
        $res .= $user_str;
        return $res;
    }

    /* 關閉點餐
     * @return string
     */
    private function closeOrder(Task $last_task)
    {
        if (isset($last_task) && $last_task->is_open !== 1) { // 確認最新訂單是否已關閉(上次的)
            return '已經結束過';
        }
        // 關閉訂單
        $last_task->is_open = 2;
        $last_task->save();
        return '已關閉';
    }

    // 取得我的lineId
    public function setUserLineId($userLineId)
    {
        $this->userLineId = $userLineId;
    }

    // 訂餐
    public function orderMeal($command, Task $last_task, $groupId)
    {
        $user = User::getUserByLineId($this->userLineId);
        if (!$user) {
            return '請先在訂餐網頁綁定Line帳號';
        }

        $OrderTask = $this->createTaskOrder($command, $last_task, $user);
        return $OrderTask->save() ? '收' : '點餐失敗';
    }

    private function createTaskOrder($command, Task $last_task, $user)
    {
        $OrderTask = new TaskOrder;
        $OrderTask->user_id = $user->id;
        $OrderTask->task_id = $last_task->id;
        list($meal_name, $meal_price, $remark) = $this->parseOrderCommand($command);
        $OrderTask->meal_name = $meal_name;
        $OrderTask->meal_price = $meal_price;
        $OrderTask->qty = 1;
        $OrderTask->remark = $remark;
        return $OrderTask;
    }

    private function parseOrderCommand($command)
    {
        $parts = explode(" ", $command);
        $meal_name = $parts[1];
        $meal_price = explode("$", $parts[2])[1];
        $remark = $parts[3] ?? '';
        return [$meal_name, $meal_price, $remark];
    }

    /**
     * 刪除餐點
     *
     * @param string $command
     * @return string
     */
    public function delOrder($command)
    {
        $info_id = explode(" ", $command)[1];
        $taskOrder = TaskOrder::find($info_id);
        if (!$taskOrder) {
            return '找不到要刪除的餐點';
        }

        $meal_name = $taskOrder->meal_name;
        $taskOrder->delete();
        return '已刪除' . self::WRAP_STR . $meal_name;
    }
    // 取得我的lineId
    public function getMyLineId()
    {
        return $this->userLineId;
    }

    /**
     * 解析 Line Webhook 資料
     *
     * @param Request $request
     * @return array
     */
    public function parseLineWebhookText(Request $request)
    {
        $event = $request->events[0];
        $messageType = $event['message']['type'];
        $message = $event['message']['text'];
        $replyToken = $event['replyToken'];
        $userId = $event['source']['userId'];

        return [
            'messageType' => $messageType,
            'message' => $message,
            'replyToken' => $replyToken,
            'userId' => $userId,
        ];
    }

    /* 確認文字是否為刪除
     * @param string $text
     * @return bool
     */
    public function isDelInfoBy($text)
    {
        $res = false;
        $inarr = explode(" ", $text);
        if ($inarr[0]) {
            switch ($inarr[0]) {
                case '刪除':
                case '刪除餐點':
                    $res = true;
                    break;
            }
        }
        return $res;
    }

    /* 確認文字是否為點餐
     * @param string $text
     * @return bool
     */
    public function isOrderInfo($text)
    {
        $res = false;
        $inarr = explode(" ", $text);
        if (isset($inarr[2]) && $inarr[2]) {
            if (substr($inarr[2], 0, 1) === '$') {
                $res = true;
            }
        }
        return $res;
    }

    /**
     * 取得 Line Login Url
     *
     * @return string
     */
    public function getLoginBaseUrl()
    {
        // 組成 Line Login Url
        $url = config('line.authorize_base_url') . '?';
        $url .= 'response_type=code';
        $url .= '&client_id=' . config('line.channel_id');
        $url .= '&redirect_uri=' . config('app.url') . '/callback/login';
        $url .= '&state=test'; // 暫時固定方便測試
        $url .= '&scope=openid%20profile';

        return $url;
    }

    public function getLineToken($code)
    {
        $client = new Client();
        $response = $client->request('POST', config('line.get_token_url'), [
            'form_params' => [
                'grant_type' => 'authorization_code',
                'code' => $code,
                'redirect_uri' => config('app.url') . '/callback/login',
                'client_id' => config('line.channel_id'),
                'client_secret' => config('line.secret'),
            ],
        ]);
        return json_decode($response->getBody()->getContents(), true);
    }

    public function getUserProfile($token)
    {
        $client = new Client();
        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ];
        $response = $client->request('GET', config('line.get_user_profile_url'), [
            'headers' => $headers,
        ]);
        return json_decode($response->getBody()->getContents(), true);
    }
}
