<?php namespace bulksms\wrapper\providers\nexmo;

use  bulksms\wrapper\Providers;

class Nexmo implements \bulksms\wrapper\providers\IProvider
{
    public function sendMsg(string $msg, array $recipientsNumber = [])
    {
        print("Nexmo send sms");
    }

    public function name(): string
    {
        return "nexmo";
    }
}

Providers::addProvider(new Nexmo());