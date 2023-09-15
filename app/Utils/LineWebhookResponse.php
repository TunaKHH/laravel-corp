<?php
namespace App\Utils;

use App\Contracts\LineWebhookResponseInterface;

class LineWebhookResponse implements LineWebhookResponseInterface
{
    private $messageType;
    private $message;
    private $replyToken;
    private $userId;

    public function __construct($messageType, $message, $replyToken, $userId)
    {
        $this->messageType = $messageType;
        $this->message = $message;
        $this->replyToken = $replyToken;
        $this->userId = $userId;
    }

    public function getMessageType()
    {
        return $this->messageType;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function getReplyToken()
    {
        return $this->replyToken;
    }

    public function getUserId()
    {
        return $this->userId;
    }
}
