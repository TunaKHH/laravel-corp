<?php
namespace App\Contracts;

interface LineWebhookResponseInterface
{
    public function getMessageType();
    public function getMessage();
    public function getReplyToken();
    public function getUserId();
}
