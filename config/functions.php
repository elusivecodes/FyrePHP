<?php
declare(strict_types=1);

use
    Fyre\Config\Config,
    Fyre\HTMLHelper\HtmlHelper,
    Fyre\Lang\Lang;

if (!function_exists('config')) {
    function config(string $key, $default)
    {
        return Config::get($key, $default);
    }
}

if (!function_exists('escape')) {
    function escape(string $string): string
    {
        return HtmlHelper::escape($string);
    }
}

if (!function_exists('lang')) {
    function lang(string $key, array $data = []): string|array|null
    {
        return Lang::get($key, $data);
    }
}
