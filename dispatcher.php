<?php namespace bulksms\dispatcher;

include_once "autoloader.php";
include_once "provider/provider.php";
include_once "provider/wrapper/provider-wrapper.php";
include_once "exception/bulksms-exception.php";

use bulksms\Config;
use bulksms\exception\BulkSmsException;
use bulksms\message\MessageStore;
use bulksms\queue\Queue;

/**
 * Class encapsulating all features that entail queue listening and message sending / updating message status.
 * The containing file is ran as an independent script.
 *
 * Class Dispatcher
 * @package bulksms\dispatcher
 */
class Dispatcher
{
    /**
     * Method listens to the queue, receives the message and sends the message to the provider configured.
     * In the case the provider is updated e.g from AfricasTalking to nexmo, the next message will use 'nexmo'
     * provider to send the message.
     *
     * If a provider is not found, the method will throw an exception.
     *
     * @throws BulkSmsException
     */
    public static function dispatch()
    {
        Queue::consume(function ($message) {
            // Fetch the provider
            $provider = \bulksms\provider\Providers::getProvider(Config::getConfigProvider());

            if (is_null($provider))
                throw new BulkSmsException("provider implementation is missing");

            $msg = json_decode($message->body);

            // Retrieve the message from the database
            $store = new MessageStore();
            $record = $store->get($msg->id);

            // Send the message using the provider set
            $status = $provider->sendMessage($record);

            // After sending the message, update the status and save back to the database.
            $record->setStatus($status);
            $record->update();
        });
    }
}

Queue::setup();
Dispatcher::dispatch();