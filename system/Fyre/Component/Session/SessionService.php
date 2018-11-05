<?php

namespace Fyre\Component\Session;

use
    Config\Services,
    Fyre\Component\Session\Exceptions\SessionException;

use function
    array_key_exists,
    property_exists;

abstract class SessionService
{
    protected static $interfaces = [];

    public static function load(?string $key = 'Component\Session', bool $shared = true, ?array $config = null): Session
    {
        if ($shared) {
            return static::loadShared($key, $config);
        }

        if ($config) {
            $config = new SessionConfig($config);
        } else {
            $config = Services::config($key, false);

            if ( ! $config) {
                SessionException::noConfig();
            }

            if ( ! $config instanceof SessionConfig) {
                SessionException::invalidConfig();
            }
        }

        if ( ! property_exists($config, 'handler')) {
            SessionException::noHandler();
        }

        $className = $config->handler;

        if ( ! class_exists($className, true)) {
            SessionException::handlerNotExists($key, $className);
        }

        return new Session($config);
    }

    protected static function &loadShared(?string $key, ?array $config): Session
    {
        if ( ! array_key_exists($key, static::$interfaces)) {
            static::$interfaces[$key] = static::load($key, false, $config);
        }

        return static::$interfaces[$key];
    }

}
