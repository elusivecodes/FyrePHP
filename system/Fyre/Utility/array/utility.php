<?php

if ( ! function_exists('array_collapse')) {
    function array_collapse(...$arrays): array
    {
        return array_replace_recursive(...$arrays);
    }
}

if ( ! function_exists('array_divide')) {
    function array_divide(array $array): array
    {
        return [
            array_keys($array),
            array_values($array)
        ];
    }
}

if ( ! function_exists('array_flatten')) {
    function array_flatten(array $array): array
    {
        return array_reduce(
            $array,
            function($a, $b) {
                return array_merge(
                    $a,
                    is_array($b) ?
                        array_flatten($b) :
                        array_wrap($b)
                );
            },
            []
        );
    }
}

if ( ! function_exists('array_random')) {
    function array_random(array $array, int $elements = null)
    {
        if (empty($array)) {
            return null;
        }

        return $elements === null ?
            $array[array_rand($array)] :
            array_only(
                $array,
                array_rand(
                    $array,
                    $elements
                )
            );
    }
}

if ( ! function_exists('array_sort')) {
    function array_sort(array $array, callable $callback = null): array
    {
        $callback ?
            uasort($array, function($a, $b) {
                return strnatcmp(
                    $callback($a),
                    $callback($b)
                );
            }) :
            natcasesort($array);
        return $array;
    }
}

if ( ! function_exists('array_wrap')) {
    function array_wrap($value): array
    {
        return is_array($value) ?
            $value :
            $value === null ?
                [] :
                [$value];
    }
}
