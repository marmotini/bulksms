<?php namespace bulksms\provider\wrapper;

use bulksms\provider\Message;

interface IProvider
{
    public function sendMsg(Message $msg);

    public function name(): string;
}
