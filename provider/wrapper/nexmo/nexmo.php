<?php namespace bulksms\provider\wrapper\nexmo;

use bulksms\Config;
use bulksms\provider\Providers;
use bulksms\provider\wrapper\IProvider;
use bulksms\provider\Message;

class Nexmo implements IProvider
{
    private $client;

    function __construct(object $config)
    {
        $apiKey = $config->apikey;
        $apiSecret = $config->apisecret;

        $credentials = new \Nexmo\Client\Credentials\Basic($apiKey, $apiSecret);
        $this->client = $client = new \Nexmo\Client($credentials);
    }

    public function sendMessage(Message $msg)
    {
        $this->client->message->send([
            'to' => $msg->getRecipients()[0],
            'text' => $msg->getMessage(),
            'from' => $msg->getFrom(),
        ]);

        print("Nexmo send sms $msg");
    }

    public function sendMessages(array $messages)
    {
        // TODO: Implement sendMessages() method.
    }

    public function name(): string
    {
        return "nexmo";
    }
}

Providers::addProvider(new Nexmo(Config::get('nexmo')));
