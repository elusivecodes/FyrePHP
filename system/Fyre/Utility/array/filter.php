<?php

if ( ! function_exists('array_where')) {
    function array_where(array $array, callable $callback): array
    {
        return array_filter(
            $array,
            $callback,
            ARRAY_FILTER_USE_BOTH
        );
    }
}

if ( ! function_exists('array_except')) {
    function array_except(array $array, array $keys): array
    {
        return array_filter(
            $array,
            function($key) {
                return ! in_array($key, $keys);
            },
            ARRAY_FILTER_USE_KEY
        );
    }
}

if ( ! function_exists('array_first')) {
    function array_first(array $array, callable $callback, $default = null)
    {
        foreach ($array AS $key => $value) {
            if ($callback($value, $key)) {
                return $value;
            }
        }

        return $default;
    }
}

if ( ! function_exists('array_last')) {
    function array_last(array $array, callable $callback, $default = null)
    {
        return array_first(
            array_reverse($array),
            $callback,
            $default
        );
    }
}

if ( ! function_exists('array_only')) {
    function array_only(array $array, array $keys): array
    {
        return array_filter(
            $array,
            function($key) {
                return in_array(
                    $key,
                    $keys
                );
            },
            ARRAY_FILTER_USE_KEY
        );
    }
}
