<?php namespace bulksms\provider\wrapper;

use bulksms\provider\Message;

/**
 * All provider wrappers that are to be implemented must always implement this interface.
 *
 * Interface IProvider
 * @package bulksms\provider\wrapper
 */
interface IProvider
{
    /**
     * Method sends the message to the respective recipients set in the message object.
     *
     * @param Message $message
     * @return string
     */
    public function sendMessage(Message $message): string;

    /**
     * Method enables sending multiple messages at once via the provider underlying api feature.
     * If the provider does not support multiple messages sending out of the box, iterate through
     * the messages and call sendMessage method.
     *
     * @param array $messages
     * @return array
     */
    public function sendMessages(array $messages): array;

    /**
     * Method returns the unique identifier of the provider which normally is the name of the provider
     * implemented, This identifier is used to differentiate the provider implementation from other
     * implementations in the library.
     *
     * @return string
     */
    public function name(): string;
}

