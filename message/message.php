<?php namespace bulksms\message;

use bulksms\Config;

/**
 * Class holds a plain object implementation of a message.
 *
 * Class Message
 * @package bulksms\provider
 */
class Message
{
    var $id;
    var $msg;
    var $recipients;
    var $from;

    var $status;

    var $uniqueChunkIdentifier;
    var $order;

    function __construct(string $msg, $from, array $recipients)
    {
        $this->recipients = $recipients;
        $this->msg = $msg;
        $this->from = $from;

        $this->status = MessageStatus::Sending;
    }

    function setId($id)
    {
        $this->id = $id;
    }

    function getId()
    {
        return $this->id;
    }

    function setMessage($msg)
    {
        $this->msg = $msg;
    }

    function getMessage(): string
    {
        return $this->msg;
    }

    function setFrom($from)
    {
        $this->from = $from;
    }

    function getFrom(): string
    {
        return $this->from;
    }

    function setRecipients(array $recipients)
    {
        $this->recipients = $recipients;
    }

    function getRecipients(): array
    {
        return $this->recipients;
    }

    function setStatus($status)
    {
        $this->status = $status;
    }

    function getStatus(): string
    {
        return $this->status;
    }

    function setUniqueChunkIdentifier(string $uid)
    {
        $this->uniqueChunkIdentifier = $uid;
    }

    function getUniqueChunkIdentifier(): string
    {
        return $this->uniqueChunkIdentifier;
    }

    function setOrder(int $order = 0)
    {
        $this->order = $order;
    }

    function getOrder(): int
    {
        return $this->order;
    }

    function save(): ?int
    {
        $store = new \bulksms\message\MessageStore();
        $this->id = $store->save($this);
        return $this->id;
    }

    function update(): bool
    {
        $store = new \bulksms\message\MessageStore();
        return $store->update($this);
    }

    public function __toString()
    {
        $recipientsStr = implode(',', $this->recipients);
        return "Message: " . $this->msg . ", recipients: " . $recipientsStr;
    }
}



