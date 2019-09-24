<?php namespace bulksms\provider;

abstract class MessageStatus
{
    const Sending = "Sending";
    const Sent = "Sent";
    const Failed = "Failed";
}

class Message
{
    private $msg;
    private $recipients;
    private $status;

    function __construct(string $msg, array $recipients)
    {
        $this->recipients = $recipients;
        $this->msg = $msg;
        $this->status = MessageStatus::Sending;
    }

    function getMessage(): string
    {
        return $this->msg;
    }

    function getRecipients(): array
    {
        return $this->recipients;
    }

    function getStatus(): string
    {
        return $this->status;
    }

    public function __toString()
    {
        $recipientsStr = implode(',', $this->recipients);
        return "Message: " . $this->msg . ", recepients: " . $recipientsStr;
    }
}