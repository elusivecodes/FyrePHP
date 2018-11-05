<?php

require 'array/data.php';
require 'array/filter.php';
require 'array/utility.php';

if ( ! function_exists('array_add')) {
    function array_add(array $array, string $key, $value): array
    {
        return $array + [$key => $value];
    }
}

if ( ! function_exists('array_prepend')) {
    function array_prepend(array $array, $value): array
    {
        return array_unshift(
            $array,
            $value
        );
    }
}

if ( ! function_exists('first')) {
    function first(array $array, $default = null)
    {
        return array_shift($array) ?? $default;
    }
}

if ( ! function_exists('last')) {
    function last(array $array, $default = null)
    {
        return array_pop($array) ?? $default;
    }
}
