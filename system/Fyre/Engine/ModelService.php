<?php

namespace Fyre\Engine;

use function
    array_key_exists,
    class_exists;

abstract class ModelService
{
    protected static $namespace = 'App\Model';
    protected static $loaded = [];

    public static function load(string $model, bool $shared = true): ?object
    {
        if ($shared) {
            return static::loadShared($model);
        }

        $className = static::$namespace.'\\'.$model;

        if ( ! class_exists($className, true)) {
            return null;
        }

        return new $className;
    }

    protected static function &loadShared(string $model): ?object
    {
        if ( ! array_key_exists($model, static::$loaded)) {
            static::$loaded[$model] = static::load($model, false);
        }

        return static::$loaded[$model];
    }

}
