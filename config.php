<?php namespace bulksms;

use bulksms\provider\wrapper\BulkSmsException;

class Config
{
    private static $configFileName = "config.json";
    private static $config;

    public static function get(string $name): object
    {
        $config = self::loadConfig();
        if (property_exists($config, $name)) {
            return $config->{$name};
        }

        return (object)[];
    }

    public static function updateConfigProvider(string $provider)
    {
        if (strlen($provider) < 1) {
            return;
        }

        $config = self::loadConfig();

        $config->provider = $provider;

        $saved = file_put_contents(self::$configFileName, json_encode($config));
        if ($saved = false) {
            echo "Failed";
        };
    }

    public static function getConfigProvider(): ?string
    {
        return self::loadConfig()->provider;
    }

    private static function loadConfig(): ?object
    {
        if (!is_null(self::$config)) {
            return self::$config;
        }

        $configContent = file_get_contents(self::$configFileName);
        if (strlen($configContent) < 1) {
            return null;
        }

        self::$config = json_decode($configContent);
        if (empty(self::$config)) {
            throw  new BulkSmsException("Config error");
        }

        return self::$config;
    }
}
