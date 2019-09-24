<?php namespace bulksms\provider\wrapper\africatalking;

use bulksms\provider\Message;
use bulksms\provider\wrapper;

use AfricasTalking\SDK\AfricasTalking;

class AfricaTalking implements \bulksms\provider\wrapper\IProvider
{
    private $service;

    function __construct(array $configs)
    {
        $username = 'username';
        $apiKey = 'apiKey';
        $this->service = new AfricasTalking($username, $apiKey);
    }

    public function sendMsg(Message $msg)
    {
        print("Africa talking send sms $msg");
    }

    public function name(): string
    {
        return "africatalking";
    }
}
