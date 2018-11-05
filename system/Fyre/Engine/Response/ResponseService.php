<?php

namespace Fyre\Engine\Response;

use
    Config\Services;

use function
    array_key_exists;

abstract class ResponseService
{
    protected static $instances = [];

    public static function load(?string $key = 'Engine\Response', bool $shared = true, ?array $config = null): Response
    {
        if ($shared) {
            return static::loadShared($key, $config);
        }

        if ($config) {
            $config = new ResponseConfig($config);
        } else {
            $config = Services::config($key, false);

            if ( ! $config) {
                ResponseException::configNotExists($key);
            }

            if ( ! $config instanceof ResponseConfig) {
                ResponseException::configInvalid($key);
            }
        }

        return new Response($config);
    }

    protected static function &loadShared(?string $key, ?array $config): Response
    {
        if ( ! array_key_exists($key, static::$instances)) {
            static::$instances[$key] = static::load($key, false, $config);
        }

        return static::$instances[$key];
    }

}
