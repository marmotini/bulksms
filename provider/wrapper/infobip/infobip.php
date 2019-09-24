<?php namespace bulksms\provider\wrapper\infobip;

use bulksms\provider\Message;
use bulksms\provider\wrapper;

class InfoBip implements \bulksms\provider\wrapper\IProvider
{
    public function sendMsg(Message $msg)
    {
        print("Infobip send sms $msg");
    }

    public function name(): string
    {
        return "infobip";
    }
}
