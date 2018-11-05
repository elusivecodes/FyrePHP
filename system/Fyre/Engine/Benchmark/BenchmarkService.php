<?php

namespace Fyre\Engine\Benchmark;

use
    Config\Services,
    Fyre\Engine\Benchmark\Exceptions\BenchmarkException;

use function
    array_key_exists;

abstract class BenchmarkService
{
    protected static $instances = [];

    public static function load(?string $key = 'Engine\Benchmark', bool $shared = true, ?array $config = null): Benchmark
    {
        if ($shared) {
            return static::loadShared($key, $config);
        }

        if ($config) {
            $config = new BenchmarkConfig($config);
        } else {
            $config = Services::config($key, false);

            if ( ! $config) {
                BenchmarkException::configNotExists($key);
            }

            if ( ! $config instanceof BenchmarkConfig) {
                BenchmarkException::configInvalid($key);
            }
        }

        return new Benchmark($config);
    }

    protected static function &loadShared(?string $key, ?array $config): Benchmark
    {
        if ( ! array_key_exists($key, static::$instances)) {
            static::$instances[$key] = static::load($key, false, $config);
        }

        return static::$instances[$key];
    }

}
