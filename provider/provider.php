<?php namespace bulksms\provider;

/**
 * Enables management of multiple providers using a tweaked command design pattern approach.
 * All provider implementations are maintained in a local private variable. The private variable
 * is an array with the key being the name of the provider implementation and rhe value being the
 * actual instance of the provider class implementation.
 *
 * The implementing provider calls the addProvider method to enable use of the provider in the
 * project by adding the provider to the pool of supported providers.
 *
 * To use an existing provider implementation, call the getProvider method and specify the name
 * of the provider implementation.
 *
 * Class Providers
 * @package bulksms\provider
 */
class Providers
{
    private static $providers = [];

    /**
     * Method adds a new provider implementation to the provider store variable. If the provider
     * being added already exists, the method will not allow re-adding of the provider implementation.
     * If the name of the provider implementation is not set or is an empty string, an exception will
     * be thrown.
     *
     * @param object $impl
     */
    public static function addProvider(object $impl)
    {
        if (is_null($impl) || $impl->name() == "") {
            throw new BulkSmsException("invalid provider implementation");
        }

        if (!array_key_exists($impl->name(), self::$providers)) {
            self::$providers[$impl->name()] = $impl;
        }
    }

    /**
     * Method retrieves a provider by specifying the provider name
     *
     * @param string $name
     * @return object|null
     */
    public static function getProvider(string $name): ?object
    {
        return self::$providers[$name];
    }
}
