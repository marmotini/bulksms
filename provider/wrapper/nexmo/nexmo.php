<?php namespace bulksms\provider\wrapper\nexmo;

use bulksms\Config;
use bulksms\provider\Providers;
use bulksms\provider\wrapper\IProvider;
use bulksms\provider\Message;

/**
 * Nexmo api wrapper. Implementation uses Nexmo php client library instead of implementing the api calls using
 * Nexmo rest api.
 *
 * Class Nexmo
 * @package bulksms\provider\wrapper\nexmo
 */
class Nexmo implements IProvider
{
    private $client;

    /**
     * Ideally, all credentials and configuration setup should happen in the constructor and not when sending a message.
     *
     * Nexmo constructor.
     */
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

// Add Nexmo provider to the pool of providers supported
Providers::addProvider(new Nexmo());
