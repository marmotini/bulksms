<?php namespace bulksms\wrapper\providers\infobip;

use bulksms\wrapper\Message;
use bulksms\wrapper\Providers;

class InfoBip implements \bulksms\wrapper\providers\IProvider
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
