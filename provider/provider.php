<?php namespace bulksms\provider;

include_once "wrapper/africatalking/africastalking.php";
include_once "wrapper/infobip/infobip.php";
include_once "wrapper/nexmo/nexmo.php";

use bulksms\provider\wrapper\africatalking\AfricaTalking;
use bulksms\provider\wrapper\infobip\InfoBip;
use bulksms\provider\wrapper\nexmo\Nexmo;
use bulksms\provider\wrapper\BulkSmsException;

Providers::addProvider(new AfricaTalking());
Providers::addProvider(new InfoBip());
Providers::addProvider(new Nexmo());

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
