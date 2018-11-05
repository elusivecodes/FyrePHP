<?php

namespace Fyre\Engine\Loader;

use
    Config\Services;

use function
    array_key_exists;

abstract class LoaderService
{
    protected static $instances = [];

    public static function load(?string $key = 'Engine/Loader', bool $shared = true): Loader
    {
        if ($shared) {
            return static::loadShared($key);
        }

        return new Loader($key);
    }

    protected static function &loadShared(?string $key): Loader
    {
        if ( ! array_key_exists($key, static::$instances)) {
            static::$instances[$key] = static::load($key, false);
        }

        return static::$instances[$key];
    }

}
