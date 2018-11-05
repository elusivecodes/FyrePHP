<?php

namespace Fyre\Component\Upload;

use
    Config\Services;

use function
    array_key_exists;

abstract class UploadService
{
    protected static $instances = [];

    public static function load(?string $key = 'Component\Upload', bool $shared = true, ?array $config = null): Upload
    {
        if ($shared) {
            return static::loadShared($key, $config);
        }

        if ($config) {
            $config = new UploadConfig($config);
        } else {
            $config = Services::config($key, false);

            if ( ! $config) {
                UploadException::noConfig();
            }
    
            if ( ! $config instanceof UploadConfig) {
                UploadException::invalidConfig();
            }    
        }

        return new Upload($config);
    }

    protected static function &loadShared(?string $key, ?array $config): Upload
    {
        if ( ! array_key_exists($key, static::$instances)) {
            static::$instances[$key] = static::load($key, false, $config);
        }

        return static::$instances[$key];
    }

}
