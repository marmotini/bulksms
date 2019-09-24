<?php namespace bulksms\provider;

class Providers
{
    private static $providers = [];

    public static function addProvider(object $impl)
    {
        if (is_null($impl) || $impl->name() == "") {
            throw new BulkSmsException("invalid provider implementation");
        }

        if (!array_key_exists($impl->name(), self::$providers)) {
            self::$providers[$impl->name()] = $impl;
        }
    }

    public static function getProvider(string $name): ?object
    {
        return self::$providers[$name];
    }
}
