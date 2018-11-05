<?php

namespace Fyre\Component\Cache;

use
    Config\Services,
    Fyre\Component\Cache\Exceptions\CacheException;

use function
    array_key_exists,
    class_exists,
    property_exists;

abstract class CacheService
{
    protected static $instances = [];

    public static function load(?string $key = 'Component\Cache', bool $shared = true, ?array $config = null): CacheHandlerInterface
    {
        if ($shared) {
            return static::loadShared($key, $config);
        }

        if ($config) {
            $config = new CacheConfig($config);
        } else {
            $config = Services::config($key, false);

            if ( ! $config) {
                CacheException::configMissing($key);
            }

            if ( ! $config instanceof CacheConfig) {
                CacheException::configInvalid($key);
            }
        }

        if ( ! property_exists($config, 'handler')) {
            CacheException::handlerMissing($key);
        }

        $className = $config->handler;

        if ( ! class_exists($className, true)) {
            CacheException::handlerNotExists($key, $className);
        }

        return new $className($config);
    }

    protected static function &loadShared(?string $key, ?array $config): CacheHandlerInterface
    {
        if ( ! array_key_exists($key, static::$instances)) {
            static::$instances[$key] = static::load($key, false, $config);
        }

        return static::$instances[$key];
    }

}
