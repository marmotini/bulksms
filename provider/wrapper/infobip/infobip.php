<?php namespace bulksms\provider\wrapper\infobip;

use bulksms\Config;
use bulksms\provider\Message;
use bulksms\provider\Providers;
use bulksms\provider\wrapper\IProvider;

/**
 * InfoBip api wrapper. Implementation uses InfoBip php client library instead of implementing the api calls using
 * InfoBip rest api.
 *
 * Class InfoBip
 * @package bulksms\provider\wrapper\infobip
 */
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

        // TODO:
        return $response;
    }

    public function sendMessages(array $messages): array
    {
        $resp = [];
        foreach ($messages as $msg) {
            $resp[] = $this->sendMessage($msg);
        }

        // TODO:
        return $resp;
    }

    public function name(): string
    {
        return "infobip";
    }
}

// Add infobip provider to the pool of providers supported
Providers::addProvider(new InfoBip());
