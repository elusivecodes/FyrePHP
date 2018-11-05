<?php

namespace Fyre\Engine\Security;

use
    Config\Services,
    Fyre\Engine\Security\Exceptions\SecurityException;

use function
    array_key_exists;

abstract class SecurityService
{
    protected static $instances = [];

    public static function load(?string $key = 'Engine\Security', bool $shared = true, ?array $config = null): Security
    {
        if ($shared) {
            return static::loadShared($key, $config);
        }

        if ($config) {
            $config = new SecurityConfig($config);
        } else {
            $config = Services::config($key, false);

            if ( ! $config) {
                SecurityException::configNotExists($key);
            }

            if ( ! $config instanceof SecurityConfig) {
                SecurityException::configInvalid($key);
            }
        }

        return new Security($config);
    }

    protected static function &loadShared(?string $key, $config): Security
    {
        if ( ! array_key_exists($key, static::$instances)) {
            static::$instances[$key] = static::load($key, false, $config);
        }

        return static::$instances[$key];
    }

}
