<?php namespace bulksms\provider;

abstract class MessageStatus
{
    const Sending = "Sending";
    const Sent = "Sent";
    const Failed = "Failed";
}

class Message
{
    private $id;
    private $msg;
    private $recipients;
    private $from;

    private $status;

    private $parent;
    private $order;

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

    function setParent(Message $parent = null)
    {
        $this->parent = $parent;
    }

    function getParent(): Message
    {
        return $this->parent;
    }

    function setOrder(int $order = 0)
    {
        $this->order = $order;
    }

    function getOrder(): int
    {
        return $this->order;
    }

    public function __toString()
    {
        $recipientsStr = implode(',', $this->recipients);
        return "Message: " . $this->msg . ", recipients: " . $recipientsStr;
    }

    public function save(){

    }
}