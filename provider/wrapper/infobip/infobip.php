<?php namespace bulksms\provider\wrapper\infobip;

use bulksms\Config;
use bulksms\provider\Message;
use bulksms\provider\Providers;
use bulksms\provider\wrapper\IProvider;

class InfoBip implements IProvider
{
    private $client;

    function __construct()
    {
        $config = Config::get($this->name());

        $auth = new \infobip\api\configuration\BasicAuthConfiguration($config->username, $config->password);
        $this->client = new \infobip\api\client\SendSingleTextualSms($auth);
    }

    public function sendMessage(Message $message): string
    {
        $destinations = array();
        foreach ($message->getRecipients() as $phoneNumber) {
            $destination = new \infobip\api\model\Destination();
            $destination->setTo($phoneNumber);
            $destinations[] = $destination;
        }

        $body = \infobip\api\model\sms\mt\send\textual\SMSTextualRequest();
        $body->setDestinations($destinations);
        $body->setText($message->getMessage());
        $body->setFrom($message->getFrom());

        $response = $this->client->execute($body);
    }

    public function sendMessages(array $messages): array
    {
        foreach ($messages as $msg) {
            $this->sendMessage($msg);
        }
    }

    public function name(): string
    {
        return "infobip";
    }
}

Providers::addProvider(new InfoBip());
