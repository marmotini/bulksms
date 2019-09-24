<?php

// Listens to queue messages and sends the sms to the client
use bulksms\Config;
use bulksms\exception\BulkSmsException;
use bulksms\provider\Providers;
use bulksms\queue\Queue;

class Dispatcher
{
    private static $provider;

    public static function dispatch()
    {
        self:$provider = Providers::getProvider(Config::getConfigProvider());

        if (is_null(self::$provider))
            throw new BulkSmsException("provider implementation is missing");

        Queue::consume(function ($message){
            self::$provider->sendMessage($message);
        });
    }
}
