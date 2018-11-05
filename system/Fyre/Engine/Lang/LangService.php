<?php

namespace Fyre\Engine\Lang;

use
    Config\Services;

use function
    array_key_exists,
    is_array;

abstract class LangService
{
    protected static $default = 'Lang';
    protected static $instances = [];

    public static function load(?string $key = null, bool $shared = true, $config = null): Lang
    {
        if ($shared) {
            return static::loadShared($key, $config);
        }

        if (is_array($config)) {
            $config = new LangConfig($config);
        } else if ( ! $config) {
            $config = Services::config($key ?? static::$default, false);
        }

        if ( ! $config) {
            // no config
        }

        if ( ! $config instanceof LangConfig) {
            // invalid config
        }

        return new Lang($config);
    }

    protected static function &loadShared(?string $key, $config): Lang
    {
        if ( ! array_key_exists($key, static::$instances)) {
            static::$instances[$key] = static::load($key, false, $config);
        }

        return static::$instances[$key];
    }

}
