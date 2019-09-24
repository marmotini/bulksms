<?php namespace bulksms\wrapper;

include_once "providers/africatalking/africastalking.php";
include_once "providers/infobip/infobip.php";
include_once "providers/nexmo/nexmo.php";

use bulksms\wrapper\providers\africatalking\AfricaTalking;
use bulksms\wrapper\providers\infobip\InfoBip;
use bulksms\wrapper\providers\nexmo\Nexmo;

Providers::addProvider(new AfricaTalking());
Providers::addProvider(new InfoBip());
Providers::addProvider(new Nexmo());

class Providers
{
    private static $providers = [];

    public static function addProvider(object $impl)
    {
        if (is_null($impl)) {
            return;
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
