<?php namespace bulksms\wrapper\providers\nexmo;

use bulksms\wrapper\Providers;
use bulksms\wrapper\Message;

class Nexmo implements \bulksms\wrapper\providers\IProvider
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
