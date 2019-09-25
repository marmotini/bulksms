<?php namespace bulksms\provider\wrapper\infobip;

use bulksms\Config;
use bulksms\message\Message;
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

    /**
     * Ideally, all credentials and configuration setup should happen in the constructor and not when sending a message.
     * InfoBip constructor.
     */
    function __construct()
    {
        $config = Config::get($this->name());

        $auth = new \infobip\api\configuration\BasicAuthConfiguration($config->username, $config->password);
        $this->client = new \infobip\api\client\SendSingleTextualSms($auth);
    }

    /**
     * Send a single message to multiple recipients.
     *
     * @param Message $message
     * @return string
     */
    public function sendMessage(Message $message): string
    {
        // Setup multiple message recipients(1 or more recipients).
        $destinations = array();
        foreach ($message->getRecipients() as $phoneNumber) {
            $destination = new \infobip\api\model\Destination();
            $destination->setTo($phoneNumber);
            $destinations[] = $destination;
        }

        // Setup message.
        $body = \infobip\api\model\sms\mt\send\textual\SMSTextualRequest();
        $body->setDestinations($destinations);
        $body->setText($message->getMessage());
        $body->setFrom($message->getFrom());

        $response = $this->client->execute($body);

        // TODO:
        return $response;
    }

    /**
     * Send multiple messages.
     *
     * @param array $messages
     * @return array
     */
    public function sendMessages(array $messages): array
    {
        $resp = [];
        foreach ($messages as $msg) {
            $resp[] = $this->sendMessage($msg);
        }

        // TODO:
        return $resp;
    }

    /**
     * Getter function that returns name of the wrapper service.
     *
     * @return string
     */
    public function name(): string
    {
        return "infobip";
    }
}

// Add infobip provider to the pool of providers supported
Providers::addProvider(new InfoBip());
