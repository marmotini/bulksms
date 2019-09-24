<?php namespace bulksms;

include_once "autoloader.php";
include_once "provider/provider.php";
include_once "provider/provider-wrapper.php";

use bulksms\provider\Message;
use bulksms\provider\Providers;

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

        try{
            $this->provider->sendMsg($message, $message->getRecipients());
        } catch (\Exception $e) {
            echo $e;
        }

    }

    function sendMessages(array $messages)
    {
        foreach ($messages as $msg) {
            $this->sendMessage($msg);
        }
    }
}

//Config::updateConfigProvider("nexmo");
Config::updateConfigProvider("africatalking");
//Config::updateConfigProvider("infobip");
$bulkSms = new BulkSms();
$bulkSms->sendMessages([new Message("Hello testing", '0746198837', ["0746198837"])]);