<?php

namespace Fyre\Mail;

use
    Config\Services;

use function
    array_key_exists,
    property_exists;

abstract class MailService
{
    protected static $instances = [];

    public static function load(?string $key = 'Component\Mail', bool $shared = true, ?array $config = null): MailHandlerInterface
    {
        if ($shared) {
            return static::loadShared($key, $config);
        }

        if ($config) {
            $config = new MailConfig($config);
        } else {
            $config = Services::config($key, false);

            if ( ! $config) {
                MailException::configMissing($key);
            }

            if ( ! $config instanceof MailConfig) {
                MailException::configInvalid($key);
            }
        }

        if ( ! property_exists($config, 'handler')) {
            MailException::handlerMissing($key);
        }

        $className = $config->handler;

        if ( ! class_exists($className, true)) {
            MailException::handlerNotExists($key, $className);
        }

        return new $className($config);
    }

    protected static function &loadShared(?string $key, ?array $config): MailHandlerInterface
    {
        if ( ! array_key_exists($key, static::$instances)) {
            static::$instances[$key] = static::load($key, false, $config);
        }

        return static::$instances[$key];
    }

}
