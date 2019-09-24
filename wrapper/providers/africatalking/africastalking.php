<?php namespace bulksms\wrapper\providers\africatalking;

use bulksms\wrapper\Providers;

class AfricaTalking implements \bulksms\wrapper\providers\IProvider
{
    public function sendMsg(string $msg, array $recipientsNumber = [])
    {
        print("Africa talking send sms");
    }

    public function name(): string
    {
        return "africatalking";
    }
}

Providers::addProvider(new AfricaTalking());