<?php namespace bulksms\provider;

include_once "autoloader.php";

/**
 * Since the providers implementation are independent and can coexist on the own without direct coupling with the
 * rest of the implementation, we would still need a way to load the implementations to make them available to the
 * project. This is the purpose of this file and notably, of the below include statements.
 *
 * To use the providers supported, include thos file in the implementing script/file.
 *
 */
include_once "africatalking/africastalking.php";
include_once "infobip/infobip.php";
include_once "nexmo/nexmo.php";
