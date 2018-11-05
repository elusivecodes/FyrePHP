<?php

namespace Fyre\Component\Image;

use
    Config\Services,
    Fyre\Component\Image\Exceptions\ImageException;

use function
    array_key_exists,
    class_exists,
    property_exists;

abstract class ImageService
{
    protected static $instances = [];

    public static function load(?string $key = 'Component\Image', bool $shared = true, ?array $config = null): ImageHandlerInterface
    {
        if ($shared) {
            return static::loadShared($key, $config);
        }

        if ($config) {
            $config = new ImageConfig($config);
        } else {
            $config = Services::config($key, false);

            if ( ! $config) {
                ImageException::configMissing($key);
            }

            if ( ! $config instanceof ImageConfig) {
                ImageException::configInvalid($key);
            }
        }

        if ( ! property_exists($config, 'handler')) {
            ImageException::handlerMissing($key);
        }

        $className = $config->handler;

        if ( ! class_exists($className, true)) {
            ImageException::handlerNotExists($key, $className);
        }

        return new $className($config);
    }

    protected static function &loadShared(?string $key, ?array $config): ImageHandlerInterface
    {
        if ( ! array_key_exists($key, static::$instances)) {
            static::$instances[$key] = static::load($key, false, $config);
        }

        return static::$instances[$key];
    }

}
