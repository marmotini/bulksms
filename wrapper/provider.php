<?php namespace bulksms\wrapper;

include_once "providers/africatalking/africastalking.php";
include_once "providers/infobip/infobip.php";
include_once "providers/nexmo/nexmo.php";

class Providers
{
    private static $providers = [];

    public static function addProvider($impl)
    {
        if (!array_key_exists($impl->name(), self::$providers)) {
            self::$providers[$impl->name()] = $impl;
        }
    }

    public static function getProvider(string $name): ?object
    {
        return self::$providers[$name];
    }
}

