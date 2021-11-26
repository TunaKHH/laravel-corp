<?php

namespace App\Http\Controllers;
use \LINE\LINEBot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use App\Line\Constant\LineHookHttpResponse;

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

//        $httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient('Jti2dZrzGrUFvuWeTbVwU9357Df+G2B0MTQOoZ/XieVWzmQF0nVGKrdj8/tH9L2o6KIrPrprSMMni85aJ9xovENjvGNLb2RsRbGXTJXvcavNEfwN1jsd1XJVlNjWnBBuSOJSsUcRd/W0/jRfSvQNDgdB04t89/1O/w1cDnyilFU=');
//
//        $this->bot = new \LINE\LINEBot($httpClient, ['channelSecret' => '0a3960a6f268e43b4c6f7f0c0860f991']);
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
                            logger('line message text in');
                            logger('message text:' . $event['message']['text']);
                            $replyToken = $event['replyToken'];
                            logger('replyToken:' . $replyToken);

                            $res = $this->bot->replyText($replyToken,'hi');
                            logger('$res:' . json_encode($res, JSON_OBJECT_AS_ARRAY));

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
