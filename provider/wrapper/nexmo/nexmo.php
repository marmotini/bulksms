<?php namespace bulksms\provider\wrapper\nexmo;

use bulksms\Config;
use bulksms\provider\Providers;
use bulksms\provider\wrapper\IProvider;
use bulksms\provider\Message;

class Nexmo implements IProvider
{
    private $client;

    function __construct()
    {
        $config = Config::get($this->name());

        $credentials = new \Nexmo\Client\Credentials\Basic($config->apikey, $config->apisecret);
        $this->client = $client = new \Nexmo\Client($credentials, ['base_api_url' => $config->url]);
    }

    public function sendMessage(Message $msg): string
    {
        foreach ($msg->getRecipients() as $recipient) {
            $this->client->message->send([
                'to' => $recipient,
                'text' => $msg->getMessage(),
                'from' => $msg->getFrom(),
            ]);
        }
    }

    public function sendMessages(array $messages): array
    {
        foreach ($messages as $msg) {
            $this->sendMessage($msg);
        }
    }

    public function name(): string
    {
        return "nexmo";
    }
}

Providers::addProvider(new Nexmo());
