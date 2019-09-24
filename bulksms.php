<?php namespace bulksms;

include_once "autoloader.php";
include_once "provider/provider.php";
include_once "provider/wrapper/provider-wrapper.php";
include_once "exception/bulksms-exception.php";

use bulksms\provider\Message;
use bulksms\provider\Providers;
use bulksms\exception\BulkSmsException;

class BulkSms
{
    private $chunkPaginationChars = 7;
    private $chunkLimit = 918;

    private $provider;

    private function getMessageChunks(Message $msg): array
    {
        if (sizeof(str_split($msg->getMessage(), $this->chunkLimit)) == 1) {
            return [$msg];
        }

        $limit = $this->chunkLimit - $this->chunkPaginationChars;

        $chunks = str_split($msg->getMessage(), $limit);
        $chunksLen = sizeof($chunks);

        $parent = null;
        $chunkMessages = [];

        for ($counter = 0, $i = 0; $i < $chunksLen; $i++) {
            $counter += 1;

            $body = '(' . $counter . '/' . $chunksLen . ')' . $chunks[$i];
            $m = new Message($body, $msg->getFrom(), $msg->getRecipients());
            if ($i == 0) {
                $parent = $m;
                $m->setParent(null);
            } else {
                $m->setParent($parent);
            }

            $m->setOrder($counter);

            $chunkMessages[] = $m;
        }

        return $chunkMessages;
    }

    private function send(array $messages)
    {
        if (sizeof($messages) > 1) {
            $this->provider->sendMessages($messages);
        } else {
            $this->provider->sendMessage($messages[0]);
        }
    }

    function sendMessage(Message $msg)
    {
        if (strlen($msg->getMessage()) == 0) {
            return;
        }

        $this->provider = Providers::getProvider(Config::getConfigProvider());

        if (is_null($this->provider))
            throw new BulkSmsException("provider implementation is missing");

        $chunks = $this->getMessageChunks($msg);

        try {
            $this->send($chunks);
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

Config::updateConfigProvider("nexmo");
//Config::updateConfigProvider("africatalking");
//Config::updateConfigProvider("infobip");
$bulkSms = new BulkSms();

$helloStr = str_repeat("Hello testing", 100);
$bulkSms->sendMessages([new Message($helloStr, '+254746198837', ["+254746198837"])]);
