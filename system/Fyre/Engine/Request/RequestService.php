<?php

namespace Fyre\Engine\Request;

use
    Config\Services,
    Fyre\Engine\Request\Exceptions\RequestException;

use function
    array_key_exists;

abstract class RequestService
{
    protected static $instances = [];

    public static function load(?string $key = 'Engine\Request', bool $shared = true, ?array $config = null): Request
    {
        if ($shared) {
            return static::loadShared($key, $config);
        }

        if ($config) {
            $config = new RequestConfig($config);
        } else {
            $config = Services::config($key, false);

            if ( ! $config) {
                RequestException::configNotExists($key);
            }

            if ( ! $config instanceof RequestConfig) {
                RequestException::configInvalid($key);
            }
        }

        return new Request($config);
    }

    protected static function &loadShared(?string $key, ?array $config): Request
    {
        if ( ! array_key_exists($key, static::$instances)) {
            static::$instances[$key] = static::load($key, false, $config);
        }

        return static::$instances[$key];
    }

}
