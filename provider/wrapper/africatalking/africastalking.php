<?php namespace bulksms\provider\wrapper\africatalking;

use bulksms\Config;
use bulksms\message\Message;
use bulksms\message\MessageStatus;
use bulksms\provider\Providers;
use bulksms\provider\wrapper\IProvider;

/**
 * InfoBip api wrapper. Implementation uses InfoBip php client library instead of implementing the api calls using
 * InfoBip rest api.
 *
 * Class AfricaTalking
 * @package bulksms\provider\wrapper\africatalking
 */
class AfricaTalking implements IProvider
{
    private $service;

    private $statusMatch = ['success' => MessageStatus::Sent];

    /**
     * Ideally, all credentials and configuration setup should happen in the constructor and not when sending a message.
     * AfricaTalking constructor.
     */
    function __construct()
    {
        $config = Config::get($this->name());
        $this->service = new \AfricasTalking\SDK\AfricasTalking($config->username, $config->apikey);
    }

    /**
     * Send a single message.
     *
     * @param Message $msg
     * @return string
     */
    public function sendMessage(Message $msg): string
    {
        $resp = $this->service->sms()->send([
            'to' => $msg->getRecipients(),
            'message' => $msg->getMessage(),
            'from' => $msg->getFrom(),
            'enqueue' => 1,
            'type' => 'text',
        ]);

        return $this->statusMatch[$resp["status"]];
    }

    /**
     * Send multiple messages.
     *
     * @param array $messages
     * @return array
     */
    public function sendMessages(array $messages): array
    {
        $responses = [];
        foreach ($messages as $msg) {
            $responses[] = $this->sendMessage($msg);
        }

        // TODO
        return $responses;
    }

    /**
     * Getter function that returns name of the wrapper service.
     *
     * @return string
     */
    public function name(): string
    {
        return "africatalking";
    }
}

// Add africastalking provider to the pool of providers supported
Providers::addProvider(new AfricaTalking());