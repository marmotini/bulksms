<?php namespace bulksms;

include_once "autoloader.php";
include_once "provider/provider.php";
include_once "provider/wrapper/provider-wrapper.php";
include_once "provider/wrapper/exception.php";

use bulksms\provider\Message;
use bulksms\provider\Providers;
use bulksms\provider\wrapper\BulkSmsException;

class BulkSms
{
    private $provider;
    private $chunkLimit = 918;

    private function getMessageChunks(Message $msg): array
    {
        $chunks = str_split($msg->getMessage(), $this->chunkLimit);
        $chunksLen = sizeof($chunks);

        if ($chunksLen == 1) {
            return [$msg];
        }

        $chunkMessages = [];
        for ($i = 0; $i < $chunksLen; $i++) {
            $body = '(' . ($i++) . '/' . $chunksLen . ')' . $chunks[$i];
            $chunkMessages[] = new Message($body, $msg->getFrom(), $msg->getRecipients());
        }

        return $chunkMessages;
    }

    function sendMessage(Message $msg)
    {
        if (strlen($msg->getMessage()) == 0) {
            return;
        }

        $this->provider = Providers::getProvider(Config::getConfigProvider());

        if (is_null($this->provider))
            throw new BulkSmsException("provider implemetation is missing");


        $chunks = $this->getMessageChunks($msg);

        try {
            if (sizeof($chunks) > 1) {
                $this->provider->sendMessages($chunks);
            } else {
                $this->provider->sendMessage($chunks[0]);
            }
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