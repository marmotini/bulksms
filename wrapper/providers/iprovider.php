<?php namespace bulksms\wrapper\providers;

interface IProvider
{
    public function sendMsg(string $msg, array $recipientsNumber = []);

    public function name(): string;
}
