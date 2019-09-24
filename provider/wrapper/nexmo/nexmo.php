<?php namespace bulksms\provider\wrapper\nexmo;

use bulksms\provider\wrapper;
use bulksms\provider\Message;

class Nexmo implements \bulksms\provider\wrapper\IProvider
{
    public function sendMsg(Message $msg)
    {
        print("Nexmo send sms $msg");
    }

    public function name(): string
    {
        return "nexmo";
    }
}
