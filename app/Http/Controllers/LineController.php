<?php

namespace App\Http\Controllers;

use App\Services\LineService;
use Illuminate\Http\Request;
use \LINE\LINEBot;

class LineController extends Controller
{
    /**
     * @var \LINE\LINEBot
     */
    private $bot;
    private LineService $lineService;

    public function __construct(LineService $lineService)
    {
        $this->lineService = $lineService;
        $httpClient = new LINEBot\HTTPClient\CurlHTTPClient(env('LINE_BOT_CHANNEL_ACCESS_TOKEN'));
        $this->bot = new LINEBot($httpClient, ['channelSecret' => env('LINE_BOT_CHANNEL_SECRET')]);
    }

    /*
     * line 傳回時會經過這裡
     */
    public function webhook(Request $request)
    {
        // 解析出要用的資料
        $parsedArr = $this->lineService->parseTextBy($request);

        // TODO 判斷文字格式是否為需要處理的指令 若不需要處理就直接回傳200成功訊息

        // 直接丟給lineService處理
        $responseText = $this->lineService->preProcessMsg($parsedArr['message'], $parsedArr['userId']);
        $this->bot->replyText($parsedArr['replyToken'], $responseText);
        // $response_text = $this->preProcessMsg($event['message']['text'], $event['source']['groupId']);

        // 回傳200成功訊息
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

}
