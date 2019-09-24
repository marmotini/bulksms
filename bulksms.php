<?php namespace bulksms;

include_once "autoloader.php";
include_once "wrapper/provider.php";

use bulksms\wrapper\Message;
use  bulksms\wrapper\Providers;

class BulkSms
{
    private $provider;

    function __construct()
    {
        $this->provider = Providers::getProvider(Config::getConfigProvider());
    }

    function sendMessage(Message $message)
    {
        if (strlen($message->getMessage()) == 0) {
            return;
        }

        $chunks = str_split($message->getMessage(), 918);
        $this->provider->sendMsg($message, $message->getRecipients());
    }

    function sendMessages(array $messages) {
        foreach ($messages as $msg) {
            $this->sendMessage($msg);
        }
    }
}

Config::updateConfigProvider("nexmo");
$bulkSms = new BulkSms();
$bulkSms->sendMessage(new Message("Hello testing", ["0746198837"]));