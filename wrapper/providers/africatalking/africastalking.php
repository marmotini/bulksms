<?php namespace bulksms\wrapper\providers\africatalking;

use bulksms\wrapper\Message;
use bulksms\wrapper\Providers;

class AfricaTalking implements \bulksms\wrapper\providers\IProvider
{
    public function sendMsg(Message $msg)
    {
        print("Africa talking send sms $msg");
    }

    public function name(): string
    {
        return "africatalking";
    }
}
