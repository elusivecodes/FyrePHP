<?php

namespace Fyre\Engine\Renderer;

use
    Config\Services,
    Fyre\Engine\Renderer\Exceptions\RendererException;

use function
    array_key_exists;

abstract class RendererService
{
    protected static $instances = [];

    public static function load(?string $key = 'Engine\Renderer', bool $shared = true, ?array $config = null): Renderer
    {
        if ($shared) {
            return static::loadShared($key, $config);
        }

        if ($config) {
            $config = new RendererConfig($config);
        } else {
            $config = Services::config($key, false);

            if ( ! $config) {
                RendererException::configNotExists($key);
            }

            if ( ! $config instanceof RendererConfig) {
                RendererException::configInvalid($key);
            }
        }

        return new Renderer($config);
    }

    protected static function &loadShared(?string $key, ?array $config): Renderer
    {
        if ( ! array_key_exists($key, static::$instances)) {
            static::$instances[$key] = static::load($key, false, $config);
        }

        return static::$instances[$key];
    }

}
