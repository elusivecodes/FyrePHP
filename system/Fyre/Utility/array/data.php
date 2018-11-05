<?php

if ( ! function_exists('array_dot')) {
    function array_dot(array $array, string $pre = null): array
    {
        $result = [];

        foreach ($array AS $key => $val) {
            if ($pre) {
                $key = $pre.'.'.$key;
            }

            if (is_array($val)) {
                $result += array_dot($val, $key);
            } else {
                $result[$key] = $val;
            }
        }

        return $result;
    }
}

if ( ! function_exists('array_forget')) {
    function array_forget(array &$array, string $key): void
    {
        $keys = explode('.', $key);

        $pointer = &$array;

        while (($current = array_shift($keys)) &&
            count($keys) > 0) {
            if ( ! array_key_exists($current, $pointer)) {
                return;
            }
    
            $pointer = &$pointer[$current];
        }

        unset($pointer[$current]);
    }
}

if ( ! function_exists('array_get')) {
    function array_get(array $array, string $key, $default = null)
    {
        $result =& $array;

        foreach (explode('.', $key) AS $key) {
            if ( ! array_key_exists($key, $result)) {
                return $default;
            }

            $result =& $result[$key];
        }

        return $result;
    }
}

if ( ! function_exists('array_has')) { 
    function array_has(array $array, string $key): bool
    {
        foreach (explode('.', $key) AS $key)
        {
            if ( ! array_key_exists($key, $array)) {
                return false;
            }

            $array =& $array[$key];
        }

        return true;
    }
}

if ( ! function_exists('array_pluck')) {
    function array_pluck(array $arrays, string $key)
    {
        $result = [];

        foreach ($arrays AS $array) {
            $result[] = array_get($array, $key);
        }

        return $result;
    }
}

if ( ! function_exists('array_set')) {
    function array_set(array &$array, string $key, $value, bool $overwrite = true): void
    {
        $keys = explode('.', $key);

        $pointer = &$array;

        while (($current = array_shift($keys)) &&
            count($keys) > 0) {
            if ($current === '*') {
                foreach ($pointer AS &$point) {
                    array_set(
                        $point,
                        implode('.', $keys),
                        $value,
                        $overwrite
                    );
                }

                return;
            }

            if ( ! array_key_exists($current, $pointer)) {
                $pointer[$current] = [];
            }

            $pointer = &$pointer[$current];
        }

        if ($overwrite || ! array_key_exists($current, $pointer)) {
            $pointer[$current] = $value;
        }
    }
}

if ( ! function_exists('data_fill')) {
    function data_fill(array &$array, string $key, $value): void
    {
        array_set($array, $key, $value, false);
    }
}

if ( ! function_exists('data_get')) {
    function data_get(array $array, string $key, $default = null)
    {
        return array_get($array, $key, $default);
    }
}

if ( ! function_exists('data_set')) {
    function data_set(array &$array, string $key, $value, $overwrite = true): void
    {
        array_set($array, $key, $value, $overwrite);
    }
}
