<?php namespace bulksms\provider\wrapper\africatalking;

use bulksms\Config;
use bulksms\provider\Message;
use bulksms\provider\Providers;
use bulksms\provider\wrapper\IProvider;

class AfricaTalking implements IProvider
{
    private $service;

    function __construct()
    {
        $config = Config::get($this->name());
        $this->service = new \AfricasTalking\SDK\AfricasTalking($config->username, $config->apikey);
    }

    public function sendMessage(Message $msg): string
    {
        return $this->service->sms()->send([
            'to' => $msg->getRecipients(),
            'message' => $msg->getMessage(),
//            'from' => $msg->getFrom(),
            'enqueue' => 1,
            'type' => 'text',
        ]);
    }

    public function sendMessages(array $messages): array
    {
        foreach ($messages as $msg) {
            $resp = $this->sendMessage($msg);
        }
    }

    public function name(): string
    {
        return "africatalking";
    }
}

Providers::addProvider(new AfricaTalking());