<?php

namespace App\Services;

use App\Models\Task;
use App\Models\TaskOrder;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class LineService
{
    /**
     * 解析 Line Webhook 資料
     *
     * @param Request $request
     * @return array
     */
    public function parseTextBy(Request $request)
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

    /* 全形半形轉換
     * @param string $strs
     * @param string $types
     * @return string
     */
    public function nf_to_wf($strs, $types = null)
    {
        $nft = array(
            "(", ")", "[", "]", "{", "}", ".", ",", ";", ":",
            "-", "?", "!", "@", "#", "$", "%", "&", "|", "\\",
            "/", "+", "=", "*", "~", "`", "'", "\"", "<", ">",
            "^", "_", "[", "]",
            "0", "1", "2", "3", "4", "5", "6", "7", "8", "9",
            "a", "b", "c", "d", "e", "f", "g", "h", "i", "j",
            "k", "l", "m", "n", "o", "p", "q", "r", "s", "t",
            "u", "v", "w", "x", "y", "z",
            "A", "B", "C", "D", "E", "F", "G", "H", "I", "J",
            "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T",
            "U", "V", "W", "X", "Y", "Z",
            " ",
        );
        $wft = array(
            "（", "）", "〔", "〕", "｛", "｝", "﹒", "，", "；", "：",
            "－", "？", "！", "＠", "＃", "＄", "％", "＆", "｜", "＼",
            "／", "＋", "＝", "＊", "～", "、", "、", "＂", "＜", "＞",
            "︿", "＿", "【", "】",
            "０", "１", "２", "３", "４", "５", "６", "７", "８", "９",
            "ａ", "ｂ", "ｃ", "ｄ", "ｅ", "ｆ", "ｇ", "ｈ", "ｉ", "ｊ",
            "ｋ", "ｌ", "ｍ", "ｎ", "ｏ", "ｐ", "ｑ", "ｒ", "ｓ", "ｔ",
            "ｕ", "ｖ", "ｗ", "ｘ", "ｙ", "ｚ",
            "Ａ", "Ｂ", "Ｃ", "Ｄ", "Ｅ", "Ｆ", "Ｇ", "Ｈ", "Ｉ", "Ｊ",
            "Ｋ", "Ｌ", "Ｍ", "Ｎ", "Ｏ", "Ｐ", "Ｑ", "Ｒ", "Ｓ", "Ｔ",
            "Ｕ", "Ｖ", "Ｗ", "Ｘ", "Ｙ", "Ｚ",
            "　",
        );

        if ($types == 'nf_to_wf') { // 轉全形
            return str_replace($nft, $wft, $strs);
        } else if ($types == 'wf_to_nf') { // 轉半形
            return str_replace($wft, $nft, $strs);
        } else {
            return $strs;
        }
    }

    public function preProcessMsg($text, $groupId)
    {
        // 換行符號
        $wrap_str = "\n";
        // 取得最新的訂單
        $last_order = Task::getLast($groupId);
        // 判斷文字指令是否為點餐
        $is_order = $this->isOrderInfo($text);
        // 判斷文字指令是否為刪除
        $is_del = $this->isDelInfoBy($text);

        $text = $this->nf_to_wf($text, 'wf_to_nf');
        switch ($text) {
            case '!$':
                $balance_list = User::getAllDeposit();
                $res = '帳號餘額' . $wrap_str;
                $res .= '------------' . $wrap_str;

                $user_str = "";
                foreach ($balance_list as $user) {
                    $user_str .= $user['name'] . ':' . $user['balance'] . $wrap_str;
                }
                $res .= $user_str;
                return $res;
            case '!{':
            case '!渴了':
            case '!餓了':
            case '!吃飯':
            case '點餐':
            case '開始點餐':
            case '開啟點餐':
                // 判斷是否有開啟的訂單
                if ($last_order->isEmpty() &&
                    $last_order->is_open === 1) {
                    return '已有開啟的訂單,請直接點餐(EX:鮪 炒飯 $100 不要蔥)';
                }
                // 開啟訂單團
                $task = new Task;
                $task->line_group_id = $groupId;
                $save_res = $task->save();
                if ($save_res) {
                    return '好,開啟了,請直接點餐(EX:鮪 炒飯 $100 不要蔥)';
                }
                return '開啟訂單失敗';
            case '!!':
            case '確認':
            case '確認餐點':
            case '確認點餐':
            case '查看':
            case '查看餐點':
            case '查看點餐':
                $res = '';
                $order_processed = '';
                $info_list = '';
                $money = 0;
                $groupby_foodname = array();
                $order_list = $last_order->taskOrder()->get();

                // 判斷$order_list 是否為空
                if ($order_list->isEmpty()) {
                    $info_list = '尚無點餐';
                    return $info_list;
                }
                foreach ($order_list as $info) {
                    // 點餐列表
                    $oneLine = [];
                    array_push($oneLine, $info->id);
                    array_push($oneLine, $info->user->name);
                    array_push($oneLine, $info->meal_name);
                    array_push($oneLine, $info->meal_price);
                    array_push($oneLine, '數量:' . $info->qty);
                    array_push($oneLine, $info->remark);
                    $info_list .= implode(' ', $oneLine) . $wrap_str;

                    // 計算金額
                    $info_money = $info['meal_price'] * $info['qty'];
                    $money = $info_money + $money;
                    // 點餐統整
                    $meal_name = $info['meal_name'];

                    $groupby_foodname[$meal_name] = ($groupby_foodname[$meal_name] ?? 0) + $info->qty;

                }
                ksort($groupby_foodname);

                foreach ($groupby_foodname as $name => $count) {
                    $order_processed .= $name . ':' . $count . $wrap_str;
                }
                $res = '訂單建立時間:' . $wrap_str . $last_order->create_time . $wrap_str . $info_list . $wrap_str;
                $res .= '----訂單整理----' . $wrap_str;
                $res .= $order_processed . $wrap_str;
                $res .= '總金額：' . $money;
                return $res;
            case '!}':
            case '關閉點餐':
            case '結束點餐':
                if (isset($last_order) && $last_order->is_open !== 1) { // 確認最新訂單是否已關閉(上次的)
                    return '已經結束過';
                } else {
                    // 關閉訂單
                    $last_order->is_open = 2;
                    $last_order->save();
                    return '已關閉';
                }
            default:
                if ($is_order) { // 是點餐
                    $OrderTask = new TaskOrder;
                    $OrderTask->task_id = $last_order->id;
                    $OrderTask->meal_name = explode(" ", $text)[1];
                    $OrderTask->meal_price = explode("$", explode(" ", $text)[2])[1];
                    $OrderTask->qty = 1;
                    $OrderTask->remark = explode(" ", $text)[3] ?? '';
                    if ($OrderTask->save()) {
                        return '收';
                    }
                    return '點餐失敗';
                }
                if ($is_del) {
                    // 是刪除

                    $info_id = explode(" ", $text)[1];

                    $info = m_sql_select_one('order_info', $info_id);

                    m_sql_update_field('order_info', 'status', '0', $info_id);

                    $res = '已刪除' . $wrap_str . $info['msg'];

                    return $res;
                }

                break;
        }
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
