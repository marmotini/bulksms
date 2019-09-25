<?php namespace bulksms;

include_once "autoloader.php";

use bulksms\provider\Message;
use bulksms\queue\Queue;

class BulkSms
{
    private $chunkPaginationChars = 7;
    private $chunkLimit = 918;

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
        foreach ($messages as $msg) {
            // TODO: Save msg to the database and update ID number
            Queue::publish($msg);
        }
    }

    function sendMessage(Message $msg)
    {
        if (strlen($msg->getMessage()) == 0) {
            return;
        }

        $this->send($this->getMessageChunks($msg));
    }

    function sendMessages(array $messages)
    {
        foreach ($messages as $msg) {
            $msg->setStatus(\bulksms\provider\MessageStatus::Sending);
            $msg->save();

            $this->sendMessage($msg);
        }
    }
}

Queue::setup();

Config::updateConfigProvider("nexmo");
//Config::updateConfigProvider("africatalking");
//Config::updateConfigProvider("infobip");
$bulkSms = new BulkSms();

$helloStr = str_repeat("Hello testing", 100);
$bulkSms->sendMessages([new Message($helloStr, '+254746198837', ["+254746198837"])]);
