<?php namespace bulksms;

class Config {
    private static $configFileName = "config.json";

    public static function updateConfigProvider(string $provider)
    {
        if (strlen($provider) < 1) {
            return;
        }

        $config = self::loadConfig();
        if (is_null($config)) {
            $config = ( object)array("provider" => $provider);
        }

        $config->provider = $provider;

        $saved = file_put_contents(self::$configFileName, json_encode($config));
        if ($saved = false) {
            echo "Failed";
        };
    }

    public static function getConfigProvider(): ?string
    {
        $config = self::loadConfig();
        if (is_null($config)) {
            return null;
        }

        return $config->provider;
    }

    private static function loadConfig(): ?object
    {
        $configContent = file_get_contents(self::$configFileName);
        if (strlen($configContent) < 1) {
            return null;
        }

        return json_decode($configContent);
    }
}