<?php

namespace Fyre\Config;

use function
    array_key_exists,
    class_exists;

abstract class ConfigService
{
    protected static $namespace = 'Config';
    protected static $default = 'App';
    protected static $instances = [];

    public static function load(?string $key = null, bool $shared = true, ...$args)
    {
        if ($shared) {
            return static::loadShared($key, ...$args);
        }

        $className = static::$namespace.'\\'.($key ?? static::$default);

        if ( ! class_exists($className, true)) {
            return null;
        }

        return new $className(...$args);
    }

    protected static function &loadShared(?string $key, ...$args)
    {
        if ( ! array_key_exists($key, static::$instances)) {
            static::$instances[$key] = static::load($key, false, ...$args);
        }

        return static::$instances[$key];
    }

}
