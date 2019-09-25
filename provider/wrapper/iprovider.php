<?php namespace bulksms\provider\wrapper;

use bulksms\provider\Message;

interface IProvider
{
    public function sendMessage(Message $message): string;

    public function sendMessages(array $messages): array;

    public function name(): string;
}

