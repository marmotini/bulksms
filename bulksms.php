<?php namespace bulksms;

include_once "autoloader.php";
include_once "wrapper/provider.php";

use  bulksms\wrapper\Providers;

class BulkSms
{
    private $provider;

    function __construct()
    {
        $this->provider = Providers::getProvider(Config::getConfigProvider());
    }


    function sendMessage(string $msg, array $recipientsNumber = [])
    {
        if (sizeof($recipientsNumber) == 0) {
            return;
        }

        $chunks = str_split($msg, 918);
        $this->provider->sendMsg($msg, $recipientsNumber);
    }
}

Config::updateConfigProvider("nexmo");
Config::updateConfigProvider("infobip");

$bulkSms = new BulkSms();

$bulkSms->sendMessage("Hello testing", ["0746198837"]);