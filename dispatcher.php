<?php namespace bulksms\dispatcher;

include_once "autoloader.php";
include_once "provider/provider.php";
include_once "provider/wrapper/provider-wrapper.php";
include_once "exception/bulksms-exception.php";

use bulksms\Config;
use bulksms\exception\BulkSmsException;
use bulksms\queue\Queue;
use function bulksms\provider\getMessage;
use function bulksms\provider\saveMessage;

class Dispatcher
{
    private static $provider;

    public static function dispatch()
    {
        self::$provider = \bulksms\provider\Providers::getProvider(Config::getConfigProvider());

        if (is_null(self::$provider))
            throw new BulkSmsException("provider implementation is missing");

        Queue::consume(function ($message) {
            $msg = getMessage($message->id);
            $status = self::$provider->sendMessage($msg);
            $msg->setStatus($status);
            saveMessage($msg);
        });
    }
}

Queue::setup();
Dispatcher::dispatch();