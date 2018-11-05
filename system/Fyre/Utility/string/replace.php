<?php

if ( ! function_exists('str_replace_array')) {
    function str_replace_array(string $search, array $replace, string $subject): string
    {
        array_walk(
            $replace,
            function ($string) use ($subject) {
                $subject = str_replace_first(
                    $search,
                    $string,
                    $subject
                );
            }
        );

        return $subject;
    }
}

if ( ! function_exists('str_replace_first')) {
    function str_replace_first(string $search, string $replace, string $subject): string
    {
        $pos = strpos($subject, $search);

        return $pos !== false ?
            substr_replace(
                $search,
                $replace,
                $pos,
                strlen($string)
            ) :
            $subject;
    }
}

if ( ! function_exists('str_replace_last')) {
    function str_replace_last(string $search, string $replace, string $subject): string
    {
        $pos = strrpos($subject, $search);

        return $pos !== false ?
            substr_replace(
                $search,
                $replace,
                $pos,
                strlen($string)
            ) :
            $subject;
    }
}
