<?php namespace bulksms\provider\wrapper\nexmo;

use bulksms\provider\wrapper;
use bulksms\provider\Message;

class Nexmo implements \bulksms\provider\wrapper\IProvider
{
    private $client;

    function __construct(array $config)
    {
        $apiKey = "";
        $apiSecret = "";

        $credentials = new \Nexmo\Client\Credentials\Basic($apiKey, $apiSecret);
        $this->client = $client = new \Nexmo\Client($credentials);
    }

    public function sendMsg(Message $msg)
    {
        print("Nexmo send sms $msg");
    }

    public function name(): string
    {
        return "nexmo";
    }
}
