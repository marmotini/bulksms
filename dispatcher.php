<?php namespace bulksms\dispatcher;

include_once "autoloader.php";
include_once "provider/provider.php";
include_once "provider/wrapper/provider-wrapper.php";
include_once "exception/bulksms-exception.php";

use bulksms\Config;
use bulksms\exception\BulkSmsException;
use bulksms\queue\Queue;

class Dispatcher
{
    private static $provider;

    public static function dispatch()
    {
        self:$provider = \bulksms\provider\Providers::getProvider(Config::getConfigProvider());

        if (is_null(self::$provider))
            throw new BulkSmsException("provider implementation is missing");

        Queue::consume(function ($message){
            self::$provider->sendMessage($message);
        });
    }
}

Queue::setup();
Dispatcher::dispatch();