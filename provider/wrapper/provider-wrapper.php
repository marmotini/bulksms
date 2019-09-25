<?php namespace bulksms\provider;

include_once "autoloader.php";

/**
 * Since the providers implementation are independent and can coexist on their own without hard coupling with the
 * rest of the library. We will need to pre-load the implementations to make the wrappers available to the
 * project. This is the purpose of the file and notably, of the below include statements.
 */
include_once "africatalking/africastalking.php";
include_once "infobip/infobip.php";
include_once "nexmo/nexmo.php";
