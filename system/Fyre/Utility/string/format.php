<?php

if ( ! function_exists('str_camelize')) {
    function str_camelize(string $string): string
    {
        return lcfirst(
            str_pascal($string)
        );
    }
}

if ( ! function_exists('str_escape')) {
    function str_escape(?string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES);
    }
}

if ( ! function_exists('str_pascal')) {
    function str_pascal(string $string): string
    {
        return str_replace(
            ' ',
            '',
            str_title(
                str_replace(
                    [
                        '_',
                        '-'
                    ],
                    ' ',
                    strtolower(
                        $string
                    )
                )
            )
        );
    }
}

if ( ! function_exists('str_random')) {
    function str_random(int $length = 16, string $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWYXZ0123456789'): string
    {
        $max = mb_strlen($chars, '8bit') - 1;

        $output = '';
        while ($length-- >= 0) {
            $output .= $chars[random_int(0, $max)];
        }

        return $output;
    }
}

if ( ! function_exists('str_slug')) {
    function str_slug(string $string, string $delimiter = '_'): string
    {
        return strtolower(
            str_replace(
                ' ',
                $delimiter,
                iconv(
                    'UTF-8',
                    'ASCII//TRANSLIT//IGNORE',
                    $string
                )
            )
        );
    }
}

if ( ! function_exists('str_title')) {
    function str_title(string $string): string
    {
        return mb_convert_case(
            $string,
            MB_CASE_TITLE
        );
    }
}
