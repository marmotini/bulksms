<?php namespace bulksms;

include_once "autoloader.php";

use bulksms\exception\BulkSmsException;
use bulksms\message\Message;
use bulksms\queue\Queue;
use function bulksms\message\getMessage;

class BulkSms
{
    // The number of character used for pagination; '(', ')', '/' and two digits twice for the current
    // and limit counts.
    private $chunkPaginationChars = 7;

    // The set chunk limit
    private $chunkLimit = 918;

    /**
     * Method converts long message into Message chunks given the limit on the number of characters allowed in a
     * single message. If the message is longer than set limit, the message is split into chunks that are
     * returned in an array.
     *
     * @param Message $msg Message object
     * @return array Message chunks in an array
     */
    private function getMessageChunks(Message $msg): array
    {
        // Check if the message is longer than the set limit. If not, return the message as the first index
        // of the returned array.
        if (sizeof(str_split($msg->getMessage(), $this->chunkLimit)) == 1) {
            return [$msg];
        }

        // Generate a unique id to identify the chunks
        $s = uniqid(time(), true);

        // Since we are adding pagination prefix to the message, take into account the number of characters
        // that are projected to be used in the pagination and take that into consideration.
        $limit = $this->chunkLimit - $this->chunkPaginationChars;

        $chunks = str_split($msg->getMessage(), $limit);
        $chunksLen = sizeof($chunks);

        $chunkMessages = [];

        for ($counter = 0, $i = 0; $i < $chunksLen; $i++) {
            $counter += 1;

            // Add a pagination prefix to the body.
            $body = '(' . $counter . '/' . $chunksLen . ')' . $chunks[$i];

            $m = new Message($body, $msg->getFrom(), $msg->getRecipients());

            $m->setOrder($counter); // Set the order of the chunks.
            $m->setUniqueChunkIdentifier($s);  // Set the same unique identifier for every messages.

            $chunkMessages[] = $m;
        }

        return $chunkMessages;
    }

    /**
     * Method receives the array of messages to send and iterates through the messages to update the status
     * and add the message to the database. Initial status of the message is set to 'Sending'.
     *
     * @param array $messages
     */
    private function send(array $messages)
    {
        foreach ($messages as $msg) {
            $msg->setStatus(\bulksms\message\MessageStatus::Sending);
            $msg->save();

            Queue::publish($msg);
            echo "Record queued";
        }
    }

    /**
     * Method checks for Message validity. If the message is valid and can be sent, the message is
     * parsed into chunks and sent.
     *
     * @param Message $msg object
     */
    function sendMessage(Message $msg)
    {
        if (strlen($msg->getMessage()) == 0) {
            return;
        }

        $this->send($this->getMessageChunks($msg));
    }

    /**
     * Method enables passing an array of message objects to the bulksms api.
     *
     * @param array $messages
     */
    function sendMessages(array $messages)
    {
        foreach ($messages as $msg) {
            $this->sendMessage($msg);
        }
    }
}

