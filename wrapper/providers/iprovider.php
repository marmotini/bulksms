<?php namespace bulksms\wrapper\providers;

use bulksms\wrapper\Message;

interface IProvider
{
    public function sendMsg(Message $msg);

    public function name(): string;
}
