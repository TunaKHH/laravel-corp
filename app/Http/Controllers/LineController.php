<?php

namespace App\Http\Controllers;
use App\Models\User;
use \LINE\LINEBot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use App\Line\Constant\LineHookHttpResponse;
use App\Models\Task;

//全形半形轉換
function nf_to_wf($strs, $types = null){
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
        " "
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
        "　"
    );

    if ( $types == 'nf_to_wf' ){// 轉全形
        return str_replace($nft, $wft, $strs);
    }else if( $types == 'wf_to_nf' ){// 轉半形
        return str_replace($wft, $nft, $strs);
    }else{
        return $strs;
    }
}

// 確認文字是否為點餐
function isOrderInfo($text){
    $res = false;
    $inarr = explode(" ",$text);
    if($inarr[2]){
        if(substr( $inarr[2], 0, 1 ) === '$'){
            $res = true;
        }
    }
    return $res;
}

// 確認文字是否為刪除
function isDelInfo($text){
    $res = false;
    $inarr = explode(" ",$text);
    if($inarr[0]){
        switch($inarr[0]){
            case '刪除':
            case '刪除餐點':
                $res = true;
                break;
        }
    }

    return $res;
}

class LineController extends Controller
{
    /**
     * @var \LINE\LINEBot
     */
    private $bot;

    public function __construct()
    {
        $httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient(env('LINE_BOT_CHANNEL_ACCESS_TOKEN'));
        $this->bot = new \LINE\LINEBot($httpClient, ['channelSecret' => env('LINE_BOT_CHANNEL_SECRET')]);
    }

    private function preProcessMsg($replyToken, $text, $groupId){
        $wrap_str = '\\n';

        $last_order = Task::getLast($groupId);
        $is_order = isOrderInfo($text);
        $is_del = isDelInfo($text);
        $param_arr['line_id']      = 'test';
        $param_arr['msg']      = $text;
        $param_arr['input_json'] = 'test';
        $param_arr['data_flow']     = 'in';
        $param_arr['groupId']     = $groupId;

        // TODO 將使用的參數 紀錄至db // m_sql_insert('record',$param_arr);
        $text = nf_to_wf($text,'wf_to_nf');
        switch($text){
            case '!$':
                $balance_list = User::getAllDeposit();
                $res = '帳號餘額' . $wrap_str;
                $res .= '------------' . $wrap_str;

                $user_str = "";
                foreach ($balance_list as $user){
                    $user_str .= $user['name'] . ':' . $user['balance'] . $wrap_str;
                }
                $res .= $user_str;

                $this->bot->replyText($replyToken, $res);

                break;
            case '!{':
            case '!渴了':
            case '!餓了':
            case '!吃飯':
            case '點餐':
            case '開始點餐':
            case '開啟點餐':
                if($last_order && $last_order['status']){// 確認最新訂單已關閉(上次的) 若未關閉 提示已經有開啟 不加開
                    $this->bot->replyText($replyToken, '已有開啟的訂單,請直接點餐(EX:鮪 炒飯 $100 不要蔥)');
                }else {
                    unset($param_arr);
                    $param_arr['groupId'] = $groupId;
                    $param_arr['status'] = true;

                    $task = new Task;
                    $task->line_group_id = $groupId;
                    $task->save();

                    $this->bot->replyText($replyToken, '好,開啟了,請直接點餐(EX:鮪 炒飯 $100 不要蔥)');
                }

                break;
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
                $money = 0 ;

                $groupby_foodname = array();

                $order_list = Task::where('line_group_id', $groupId)->taskOrder();
//                getInfosByOrder($last_order['id']);

                if(count($order_list) == 0 ){
                    $info_list = '尚無點餐';
                }else{
                    foreach ($order_list as $info ){
                        // 點餐列表
                        $info_list .= $info['id'] .' '. $info['msg'] . $wrap_str;

                        // 計算金額
                        $info_money = intval(explode("$",explode(" ",$info['msg'])[2])[1]);
                        $money = $info_money + $money;

                        // 點餐統整
                        $foodname = explode(" ",$info['msg'])[1];
                        if( isset($groupby_foodname[$foodname]) ){
                            $groupby_foodname[$foodname]++;
                        }else{
                            $groupby_foodname[$foodname] = 1 ;
                        }
                    }
                    ksort($groupby_foodname);
                }

                foreach ($groupby_foodname as $name => $count){
                    $order_processed .= $name . ':' . $count . $wrap_str;
                }
                $res = '訂單建立時間:' . $wrap_str . $last_order['create_time'] . $wrap_str.$wrap_str . $info_list . $wrap_str;
                $res .= '----訂單整理----'.$wrap_str;
                $res .= $order_processed . $wrap_str;
                $res .= '總金額：' . $money;

                $this->bot->replyText($replyToken, $res);
                break;
            case '!}':
            case '關閉點餐':
            case '結束點餐':
                if($last_order && $last_order['status'] == 0){// 確認最新訂單是否已關閉(上次的)
                    $this->bot->replyText($replyToken, '已經結束過');
                }else{
                    m_sql_update_field('order_list', 'status', '0', $last_order['id']);
                    $this->bot->replyText($replyToken, '已關閉');
                }

                break;
            case '刪除餐點':
                $this->bot->replyText($replyToken, '尚未支援');
                break;
            default:
                if($is_order){ // 是點餐
                    unset($param_arr);
                    $param_arr['order_id'] = $last_order['id'];
                    $param_arr['msg'] = $text;
                    m_sql_insert('order_info',$param_arr);
                    $res = '收';
                    $this->bot->replyText($replyToken, $res);
                }
                if($is_del){
                    // 是刪除
                    $param_arr['order_id'] = $last_order['id'];
                    $param_arr['msg'] = $text;

                    $info_id = explode(" ",$text)[1];

                    $info = m_sql_select_one('order_info',$info_id);

                    m_sql_update_field('order_info', 'status', '0', $info_id);

                    $this->bot->replyText($replyToken, '已刪除'. $wrap_str.$info['msg']);
                }

                break;
        }
    }
    /*
     * line 傳回時會經過這裡
     */
    public function webhook(Request $request)
    {
        logger('hi webhook');

        $events = $request->events;
        foreach ($events as $event) {
            switch($event['type']){
                case 'message':
                    logger('line message in777');
                    switch ($event['message']['type']) {
                        case 'text':
                            $reply_token = $event['replyToken'];
                            logger('line message text in');
                            logger('message text:' . $event['message']['text']);
                            logger('replyToken:' . $reply_token);

                            $this->preProcessMsg($reply_token, $event['message']['text'], $event['source']['groupId']);
//                            $this->bot->replyText($reply_token,'hi');
//                            $res = $this->bot->replyText($reply_token,'hi');

//                            logger('$res:' . json_encode($res, JSON_OBJECT_AS_ARRAY));

                            break;
                    }
                    break;
            }
        }
        return response()->json(['message' => 'hi line']);
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
