<?php

if ( ! function_exists('str_after')) {
    function str_after(string $subject, string $string): string
    {
        return substr(
            strstr(
                $subject,
                $string
            ),
            strlen($string)
        );
    }
}

if ( ! function_exists('str_before')) {
    function str_before(string $subject, string $string): string
    {
        return strstr(
            $subject,
            $string,
            true
        );
    }
}

if ( ! function_exists('str_begin')) {
    function str_begin(string $subject, string $string): string
    {
        return str_begins($subject, $string) ?
            $subject :
            $string.$subject;
    }
}

if ( ! function_exists('str_begins')) {
    function str_begins(string $subject, string $match): bool
    {
        return substr(
            $subject,
            0,
            strlen($match)
        ) === $match;
    }
}

if ( ! function_exists('str_contains')) {
    function str_contains(string $subject, string $match): bool
    {
        return strpos($subject, $match) !== false;
    }
}

if ( ! function_exists('str_contains_array')) {
    function str_contains_array(string $subject, array $matches): bool
    {
        return (bool) array_first(
            $matches,
            function($match) {
                return str_contains($subject, $match);
            }
        );
    }
}

if ( ! function_exists('str_end')) {
    function str_end(string $subject, string $string): string
    {
        return str_ends($subject, $string) ?
            $subject :
            $subject.$string;
    }
}

if ( ! function_exists('str_ends')) {
    function str_ends(string $subject, string $match): bool
    {
        return substr(
            $subject,
            -strlen($match)
        ) === $match;
    }
}

if ( ! function_exists('str_limit')) {
    function str_limit(string $string, int $limit = 100, string $append = '...')
    {
        return strlen($string) > $limit ?
            substr(
                $string,
                0,
                $limit
            ).$append :
            $string;
    }
}
