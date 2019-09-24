<?php namespace bulksms\wrapper;

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

    function __construct($msg, $recipients)
    {
        $this->recipients = $recipients;
        $this->msg = $msg;
        $this->status = MessageStatus::Sending;
    }
}