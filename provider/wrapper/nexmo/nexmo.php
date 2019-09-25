<?php namespace bulksms\provider\wrapper\nexmo;

use bulksms\Config;
use bulksms\provider\Providers;
use bulksms\provider\wrapper\IProvider;
use bulksms\message\Message;

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

    /**
     * Send a single message to respective recipients. Nexmo php lib does no support multiple recipients thus
     * loop through all recipients and send the message to all.
     *
     * @param Message $msg
     * @return string
     * @throws \Nexmo\Client\Exception\Request
     * @throws \Nexmo\Client\Exception\Server
     */
    public function sendMessage(Message $msg): string
    {
        $resp = [];
        foreach ($msg->getRecipients() as $recipient) {
            $resp[] = $this->client->message->send([
                'to' => $recipient,
                'text' => $msg->getMessage(),
                'from' => $msg->getFrom(),
            ]);
        }

        // TODO
        return implode(", ", $resp);
    }

    /**
     * Send multiple messages.
     *
     * @param array $messages
     * @return array
     * @throws \Nexmo\Client\Exception\Request
     * @throws \Nexmo\Client\Exception\Server
     */
    public function sendMessages(array $messages): array
    {
        $resp = [];
        foreach ($messages as $msg) {
            $resp[] = $this->sendMessage($msg);
        }

        return $resp;
    }

    /**
     * Getter function that returns name of the wrapper service.
     *
     * @return string
     */
    public function name(): string
    {
        return "nexmo";
    }
}

// Add Nexmo provider to the pool of providers supported
Providers::addProvider(new Nexmo());
