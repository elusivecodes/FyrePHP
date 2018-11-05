<?php

namespace Fyre\Engine\Logger;

use
    Config\Services,
    Fyre\Engine\Logger\Exceptions\LoggerException;

use function
    array_key_exists;

abstract class LoggerService
{
    protected static $instances = [];

    public static function load(?string $key = 'Engine\Logger', bool $shared = true, ?array $config = null): Logger
    {
        if ($shared) {
            return static::loadShared($key, $config);
        }

        if ($config) {
            $config = new LoggerConfig($config);
        } else {
            $config = Services::config($key, false);

            if ( ! $config) {
                LoggerException::configNotExists($key);
            }

            if ( ! $config instanceof LoggerConfig) {
                LoggerException::configInvalid($key);
            }
        }

        return new Logger($config);
    }

    protected static function &loadShared(?string $key, ?array $config): Logger
    {
        if ( ! array_key_exists($key, static::$instances)) {
            static::$instances[$key] = static::load($key, false, $config);
        }

        return static::$instances[$key];
    }

}
