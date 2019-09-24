<?php namespace bulksms\provider\wrapper\infobip;

use bulksms\Config;
use bulksms\provider\Message;
use bulksms\provider\Providers;
use bulksms\provider\wrapper\IProvider;

class InfoBip implements IProvider
{
    private $client;

    function __construct(object $config)
    {
        $user = $config->username;
        $pass = $config->password;

        $auth = new \infobip\api\configuration\BasicAuthConfiguration($user, $pass);
        $this->client = new \infobip\api\client\SendSingleTextualSms($auth);
    }

    public function sendMessage(Message $message)
    {
        $body = \infobip\api\model\sms\mt\send\textual\SMSTextualRequest();
        $body->setTo($message->getRecipients()[0]);
        $body->setText($message->getMessage());
        $body->setFrom($message->getFrom());

        $response = $this->client->execute($body);

        print("Infobip send sms $message");
    }

    public function sendMessages(array $messages)
    {

    }

    public function name(): string
    {
        return "infobip";
    }
}

Providers::addProvider(new InfoBip(Config::get('infobip')));