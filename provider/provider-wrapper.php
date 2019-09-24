<?php namespace bulksms\provider;

include_once "wrapper/africatalking/africastalking.php";
include_once "wrapper/infobip/infobip.php";
include_once "wrapper/nexmo/nexmo.php";
include_once "vendor/autoload.php";

use bulksms\provider\Providers;
use bulksms\provider\wrapper\africatalking\AfricaTalking;
use bulksms\provider\wrapper\infobip\InfoBip;
use bulksms\provider\wrapper\nexmo\Nexmo;

Providers::addProvider(new AfricaTalking([]));
Providers::addProvider(new InfoBip([]));
Providers::addProvider(new Nexmo([]));
