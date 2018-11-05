<?php

namespace Fyre\Component\Validation;

use
    Config\Services;

use function
    array_key_exists;

abstract class ValidationService
{
    protected static $instances = [];

    public static function load(?string $key = null, bool $shared = true, ?array $rules = null): Validation
    {
        if ($shared) {
            return static::loadShared($key, $rules);
        }

        if ($rules) {
            $rules = new RuleSet($rules);
        } else if ($key) {
            $rules = Services::config($key, false);

            if ($rules && ! $rules instanceof RuleSet) {
                ValidationException::invalidRules();
            }
        }

        return new Validation($rules);
    }

    protected static function &loadShared(?string $key, ?array $rules): Validation
    {
        if ( ! array_key_exists($key, static::$instances)) {
            static::$instances[$key] = static::load($key, false, $rules);
        }

        return static::$instances[$key];
    }

}
