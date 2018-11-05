<?php

namespace Fyre\Component\Encryption;

use
    Config\Services,
    Fyre\Component\Encryption\Exceptions\EncryptionException;

use function
    array_key_exists,
    class_exists,
    property_exists;

abstract class EncryptionService
{
    protected static $instances = [];

    public static function load(?string $key = 'Component\Encryption', bool $shared = true, ?array $config = null): EncryptionHandlerInterface
    {
        if ($shared) {
            return static::loadShared($key, $config);
        }

        if ($config) {
            $config = new EncryptionConfig($config);
        } else {
            $config = Services::config($key, false);

            if ( ! $config) {
                EncryptionException::configMissing($key);
            }

            if ( ! $config instanceof EncryptionConfig) {
                EncryptionException::configInvalid($key);
            }
        }

        if ( ! property_exists($config, 'handler')) {
            EncryptionException::handlerMissing($key);
        }

        $className = $config->handler;

        if ( ! class_exists($className, true)) {
            EncryptionException::handlerNotExists($key, $className);
        }

        return new $className($config);
    }

    protected static function &loadShared(?string $key, ?array $config): EncryptionHandlerInterface
    {
        if ( ! array_key_exists($key, static::$instances)) {
            static::$instances[$key] = static::load($key, false, $config);
        }

        return static::$instances[$key];
    }

}
