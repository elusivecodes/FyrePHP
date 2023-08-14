<?php
declare(strict_types=1);

use Fyre\Config\Config;
use Fyre\Lang\Lang;
use Fyre\Utility\HtmlHelper;

/**
 * Retrieve a value from the config using "dot" notation.
 * @param string $key The config key.
 * @param mixed $default The default value.
 * @return mixed The config value.
 */
function config(string $key, $default): mixed
{
    return Config::get($key, $default);
}

/**
 * Escape characters in a string for use in HTML.
 * @param string $string The input string.
 * @return string The escaped string.
 */
function escape(string $string): string
{
    return HtmlHelper::escape($string);
}

/**
 * Get a language value.
 * @param string $key The language key.
 * @param array $data The data to insert.
 * @return string|array|null The formatted language string.
 */
function lang(string $key, array $data = []): string|array|null
{
    return Lang::get($key, $data);
}
