<?php

namespace Fyre\Engine\Router;

use
    Config\Services;

use function
    array_key_exists;

abstract class RouterService
{
    protected static $instances = [];

    public static function load(?string $key = 'Engine/Router', bool $shared = true): Router
    {
        if ($shared) {
            return static::loadShared($key);
        }

        return new Router($key);
    }

    protected static function &loadShared(?string $key): Router
    {
        if ( ! array_key_exists($key, static::$instances)) {
            static::$instances[$key] = static::load($key, false);
        }

        return static::$instances[$key];
    }

}
