<?php

namespace Fyre\Database;

use
    Config\Services,
    Fyre\Database\Exceptions\DatabaseExceptions;

use function
    array_key_exists,
    is_array,
    property_exists;

abstract class DatabaseService
{
    protected static $default = 'Database\Database';
    protected static $handlers = [];

    public static function load(?string $key = null, bool $shared = true, $config = null): DatabaseHandler
    {
        if ($shared) {
            return static::loadShared($key, $config);
        }

        if ($config) {
            $config = new DatabaseConfig($config);
        } else if ( ! $config) {
            $config = Services::config($key ?? static::$default, false);
 
            if ( ! $config) {
                DatabaseException::noConfig();
            }

            if ( ! $config instanceof DatabaseConfig) {
                DatabaseException::invalidConfig();
            }    
        }

        if ( ! property_exists($config, 'handler')) {
            DatabaseException::noHandler();
        }

        $className = $config->handler;

        if ( ! class_exists($className, true)) {
            DatabaseException::handlerNotExists($key, $className);
        }

        return new $className($config);
    }

    protected static function &loadShared(?string $key, $config): DatabaseHandler
    {
        if ( ! array_key_exists($key, static::$handlers)) {
            static::$handlers[$key] = static::load($key, false, $config);
        }

        return static::$handlers[$key];
    }

}
