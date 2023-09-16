<?php

namespace App\Http\Controllers;

use App\Contracts\LineWebhookResponseInterface;
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
            logger('line webhook');
            // 解析line傳來的訊息
            $parsedArr = $this->parseLineMessage($request);
            // 判斷訊息是否要處理
            if ($this->isMessageProcessable($parsedArr)) {
                logger('line webhook processable');
                // 處理line傳回的訊息
                $this->processLineMessage($parsedArr);
            }
        } catch (\Exception $e) {
            logger()->error($e);
        } finally {
            // 回傳200 確認訊息已收到
            return response()->json();
        }
    }

    /*
     * 解析line傳回的訊息
     * @param Request $request
     * @return LineWebhookResponseInterface
     */
    private function parseLineMessage(Request $request): LineWebhookResponseInterface
    {
        // 解析出要用的資料
        return $this->lineService->parseLineWebhookText($request);
    }

    /*
     * 判斷訊息是否要處理
     * @param LineWebhookResponseInterface $parsedArr
     * @return bool
     */
    private function isMessageProcessable(LineWebhookResponseInterface $parsedArr)
    {
        // TODO: Add logic to determine if the message should be processed
        return true;
    }

    /*
     * 處理line傳回的訊息
     * @param $parsedArr
     * @return void
     */
    private function processLineMessage(LineWebhookResponseInterface $parsedArr)
    {
        logger('line webhook processLineMessage');
        // 設定user的line id到service
        $this->lineService->setUserLineId($parsedArr->getUserId());
        logger('line webhook processLineMessage set user line id');
        // 處理訊息
        try {
            $responseText = $this->lineService->handleCommands($parsedArr->getMessage(), $parsedArr->getUserId());
        } catch (\Exception $e) {
            logger()->error($e);
            $responseText = '發生錯誤，請稍後再試';
        }
        logger('line webhook processLineMessage handleCommands');
        logger('$responseText: ' . $responseText);
        logger('$parsedArr->getReplyToken(): ' . $parsedArr->getReplyToken());
        // 回傳訊息
        $response = $this->bot->replyText($parsedArr->getReplyToken(), $responseText);
        logger('line webhook processLineMessage replyText');
        logger('$response: ' . $response->getJSONDecodedBody());
    }

}
