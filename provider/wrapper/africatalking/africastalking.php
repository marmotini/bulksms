<?php namespace bulksms\provider\wrapper\africatalking;

use bulksms\Config;
use bulksms\provider\Message;
use bulksms\provider\Providers;
use bulksms\provider\wrapper\IProvider;

class AfricaTalking implements IProvider
{
    private $service;

    function __construct(object $config)
    {
        $username = $config->username;
        $apiKey = $config->apikey;
        $this->service = new \AfricasTalking\SDK\AfricasTalking($username, $apiKey);
    }

    public function sendMsg(Message $msg)
    {
        $this->service->sms()->send([
            'to' => $msg->getRecipients()[0],
            'message' => $msg->getMessage(),
            'from' => $msg->getFrom(),
            'enqueue' => 1,
        ]);
    }

    public function name(): string
    {
        return "africatalking";
    }
}

Providers::addProvider(new AfricaTalking(Config::get('africatalking')));