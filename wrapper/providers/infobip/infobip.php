<?php namespace bulksms\wrapper\providers\infobip;

use  bulksms\wrapper\Providers;

class InfoBip implements \bulksms\wrapper\providers\IProvider
{
    public function sendMsg(string $msg, array $recipientsNumber = [])
    {
        print("Infobip send sms");
    }

    public function name(): string
    {
        return "infobip";
    }
}

Providers::addProvider(new InfoBip());
