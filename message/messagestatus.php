<?php namespace bulksms\message;

/**
 * MessageStatus enum implementation. PHP doesn't do well on enums so, use this as an easy work around.
 *
 * Class MessageStatus
 * @package bulksms\provider
 */
abstract class MessageStatus
{
    const Sending = "Sending";
    const Sent = "Sent";
    const Failed = "Failed";
}
