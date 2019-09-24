<?php namespace bulksms\provider\wrapper\africatalking;

use bulksms\provider\Message;
use bulksms\provider\wrapper;

class AfricaTalking implements \bulksms\provider\wrapper\IProvider
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
