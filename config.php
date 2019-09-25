<?php namespace bulksms;

use bulksms\provider\wrapper\BulkSmsException;

/**
 * Encapsulate all features related to configuration management that is loading and updating config parameters.
 *
 * Class Config
 * @package bulksms
 */
class Config
{
    private static $configFileName = "config.json";

    /**
     * Method receives a tag name that is associated to a key in the config.json file. If the key exists,
     * retrieve the config object / data.
     *
     * @param string $name
     * @return object
     */
    public static function get(string $name): object
    {
        $config = self::loadConfig();
        if (property_exists($config, $name)) {
            return $config->{$name};
        }

        return (object)[];
    }

    /**
     * Method is used as a helper utility to enable easy update of provider in the config.
     * The underlying implementation uses a config file to save the provider and other configuration
     * details. When scaling the app horizontally, it might be better to save these details in a database.
     *
     * @param string $provider
     */
    public static function updateConfigProvider(string $provider)
    {
        // If the provider name is empty, don't continue.
        if (strlen($provider) < 1) {
            return;
        }

        // Load the existing configs so that we can update from that content
        $config = self::loadConfig();

        $config->provider = $provider;

        // Save back the content after updating the provider name
        $saved = file_put_contents(self::$configFileName, json_encode($config));
        if ($saved = false) {
            echo "Failed";
        };
    }

    /**
     * Method is a helper utility to directly retrieve the provider name from the config files
     *
     * @return string|null
     */
    public static function getConfigProvider(): ?string
    {
        return self::loadConfig()->provider;
    }

    /**
     * Method fetches and returns config object by getting config json file content and converting
     * the content into an object for easy use.
     *
     * @return object|null
     */
    private static function loadConfig(): ?object
    {
        // Retrieve config content from file.
        $configContent = file_get_contents(self::$configFileName);
        if (strlen($configContent) < 1) {
            return null;
        }

        // Convert the array content to an object.
        $config = json_decode($configContent);
        if (empty($config)) {
            throw  new BulkSmsException("Config error");
        }

        return $config;
    }
}
