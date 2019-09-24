<?php namespace bulksms\provider\wrapper;

use bulksms\provider\Message;

interface IProvider
{
    public function sendMessage(Message $message);

    public function sendMessages(array $messages);

    public function name(): string;
}
