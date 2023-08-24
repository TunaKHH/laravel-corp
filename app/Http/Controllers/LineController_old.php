<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use \LINE\LINEBot;

class LineController extends Controller
{
    /**
     * @var \LINE\LINEBot
     */
    private $bot;

    public function __construct()
    {
        $httpClient = new LINEBot\HTTPClient\CurlHTTPClient(env('LINE_BOT_CHANNEL_ACCESS_TOKEN'));
        $this->bot = new LINEBot($httpClient, ['channelSecret' => env('LINE_BOT_CHANNEL_SECRET')]);
    }

//全形半形轉換
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

// 確認文字是否為點餐
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

// 確認文字是否為刪除
    public function isDelInfo($text)
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

    private function preProcessMsg($text, $groupId)
    {
        // 換行符號
        $wrap_str = '\\n';
        // 取得最新的訂單
        $last_order = Task::getLast($groupId);
        logger('last_order:' . json_encode($last_order, JSON_OBJECT_AS_ARRAY));
        // 判斷文字指令是否為點餐
        $is_order = $this->isOrderInfo($text);
        // 判斷文字指令是否為刪除
        $is_del = $this->isDelInfo($text);
        $param_arr['line_id'] = 'test';
        $param_arr['msg'] = $text;
        $param_arr['input_json'] = 'test';
        $param_arr['data_flow'] = 'in';
        $param_arr['groupId'] = $groupId;

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
                if ($last_order && $last_order['status']) { // 確認最新訂單已關閉(上次的) 若未關閉 提示已經有開啟 不加開
                    return '已有開啟的訂單,請直接點餐(EX:鮪 炒飯 $100 不要蔥)';
                } else {
                    unset($param_arr);
                    $param_arr['groupId'] = $groupId;
                    $param_arr['status'] = true;

                    $task = new Task;
                    $task->line_group_id = $groupId;
                    $task->save();
                    return '好,開啟了,請直接點餐(EX:鮪 炒飯 $100 不要蔥)';
                }

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
                // $order_list = Task::where('line_group_id', $groupId)->taskOrder();
                $order_list = [];
//                getInfosByOrder($last_order['id']);

                if (count($order_list) == 0 || count($last_order) == 0) {
                    $info_list = '尚無點餐';
                    return $info_list;
                } else {
                    foreach ($order_list as $info) {
                        // 點餐列表
                        $info_list .= $info['id'] . ' ' . $info['msg'] . $wrap_str;

                        // 計算金額
                        $info_money = intval(explode("$", explode(" ", $info['msg'])[2])[1]);
                        $money = $info_money + $money;

                        // 點餐統整
                        $foodname = explode(" ", $info['msg'])[1];
                        if (isset($groupby_foodname[$foodname])) {
                            $groupby_foodname[$foodname]++;
                        } else {
                            $groupby_foodname[$foodname] = 1;
                        }
                    }
                    ksort($groupby_foodname);
                }

                foreach ($groupby_foodname as $name => $count) {
                    $order_processed .= $name . ':' . $count . $wrap_str;
                }
                $res = '訂單建立時間:' . $wrap_str . $last_order['create_time'] . $wrap_str . $wrap_str . $info_list . $wrap_str;
                $res .= '----訂單整理----' . $wrap_str;
                $res .= $order_processed . $wrap_str;
                $res .= '總金額：' . $money;
                return $res;
            case '!}':
            case '關閉點餐':
            case '結束點餐':
                if ($last_order && $last_order['is_open'] == 0) { // 確認最新訂單是否已關閉(上次的)
                    return '已經結束過';
                } else {
                    // 關閉訂單
                    $last_order->is_open = 0;
                    $last_order->save();
                    return '已關閉';
                }
            default:
                if ($is_order) { // 是點餐
                    unset($param_arr);
                    $param_arr['order_id'] = $last_order['id'];
                    $param_arr['msg'] = $text;
                    // m_sql_insert('order_info', $param_arr); // TODO 這裡要新增訂單
                    $res = '收';
                    return $res;
                }
                if ($is_del) {
                    // 是刪除
                    $param_arr['order_id'] = $last_order['id'];
                    $param_arr['msg'] = $text;

                    $info_id = explode(" ", $text)[1];

                    $info = m_sql_select_one('order_info', $info_id);

                    m_sql_update_field('order_info', 'status', '0', $info_id);

                    $res = '已刪除' . $wrap_str . $info['msg'];

                    return $res;
                }

                break;
        }
    }

    /*
     * line 傳回時會經過這裡
     */
    public function webhook(Request $request)
    {
//        return response()->json(['message' => 'hi line', 'request' => $request->all()]);
        logger('hi webhook');
        logger($request->all());
        // return response()->json(['message' => 'hi line']);

        $events = $request->events;
        foreach ($events as $event) {
            switch ($event['type']) {
                case 'message':
                    switch ($event['message']['type']) {
                        case 'text':
                            $reply_token = $event['replyToken'];
                            logger('line message text in');
                            logger('message text:' . $event['message']['text']);
                            logger('replyToken:' . $reply_token);
                            $response_text = $this->preProcessMsg($event['message']['text'], $event['source']['groupId']);
                            $this->bot->replyText($reply_token, $response_text);

//                            logger('$res:' . json_encode($res, JSON_OBJECT_AS_ARRAY));

                            break;
                    }
                    break;
            }
        }
        return response()->json(['message' => 'hi line']);
    }

    /*
     * 使用line群組訂餐
     */
    public function addOrder(Request $request)
    {
        // 判斷有無訂單
        // 若有多個的開啟訂單 回傳只能用網頁下訂
        // 若是單個開啟訂單 就能成功下訂

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
