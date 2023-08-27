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

    /**
     * @OA\Post(
     *   path="/webhook",
     *   summary="處理Line bot的webhook",
     *   tags={"Webhook"},
     *   @OA\RequestBody(
     *       description="Request data from Line",
     *       required=true
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Successful operation",
     *     @OA\JsonContent(type="object", @OA\Property(property="message", type="string", description="Response message"))
     *   ),
     *   @OA\Response(
     *     response=500,
     *     description="Server error",
     *     @OA\JsonContent(type="object", @OA\Property(property="error", type="string", description="Error message"))
     *   )
     * )
     */
    public function webhook(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $parsedArr = $this->parseLineMessage($request);
            if ($this->isMessageProcessable($parsedArr)) {
                $this->processLineMessage($parsedArr);
            }
            return response()->json(['message' => 'hi line']);
        } catch (\Exception $e) {
            logger()->error($e);
            return response()->json(['error' => 'An error occurred'], 500);
        }
    }

    /*
     * 解析line傳回的訊息
     * @param Request $request
     * @return array
     */
    private function parseLineMessage(Request $request)
    {
        // 解析出要用的資料
        return $this->lineService->parseLineWebhookText($request);
    }

    /*
     * 判斷訊息是否要處理
     * @param $parsedArr
     * @return bool
     */
    private function isMessageProcessable($parsedArr)
    {
        // TODO: Add logic to determine if the message should be processed
        return true;
    }

    /*
     * 處理line傳回的訊息
     * @param $parsedArr
     * @return void
     */
    private function processLineMessage($parsedArr)
    {
        // 設定user的line id
        $this->lineService->setUserLineId($parsedArr['userId']);
        $responseText = $this->lineService->handleCommands($parsedArr['message'], $parsedArr['userId']);
        $this->bot->replyText($parsedArr['replyToken'], $responseText);
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
