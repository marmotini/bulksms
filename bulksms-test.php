<?php namespace bulksms;

include_once "bulksms.php";

use bulksms\BulkSms;
use bulksms\Config;
use bulksms\message\Message;
use bulksms\queue\Queue;

Queue::setup();

//Config::updateConfigProvider("nexmo");
Config::updateConfigProvider("africatalking");
//Config::updateConfigProvider("infobip");
$bulkSms = new BulkSms();

$helloStr = str_repeat("Hello testing", 2);
$bulkSms->sendMessages([new Message($helloStr, '+254746198837', ["+254746198837"])]);