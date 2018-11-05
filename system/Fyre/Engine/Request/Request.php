<?php

namespace Fyre\Engine\Request;

use
    Config\Services;

use const
    INPUT_COOKIE,
    INPUT_GET,
    INPUT_POST,
    INPUT_SERVER,
    PHP_SAPI,
    PHP_URL_PATH;

use function
    array_key_exists,
    array_slice,
    count,
    explode,
    file_get_contents,
    filter_input,
    implode,
    json_decode,
    ltrim,
    parse_url,
    rtrim,
    str_replace,
    strtolower,
    strtoupper,

    config;

class Request implements RequestInterface
{
    private $config;

    private $segments;

    public function __construct(RequestConfig &$config)
    {
        $this->config =& $config;

        $this->segments = array_slice(
            explode(
                '/',
                ltrim(
                    parse_url(
                        $_SERVER['REQUEST_URI'],
                        PHP_URL_PATH
                    ),
                    '/'
                )
            ),
            count(
                explode(
                    '/',
                    rtrim(
                        str_replace(
                            ['https://', 'http://', '//'],
                            '',
                            config('baseUrl')
                        ),
                        '/'
                    )
                )
            )
            - 1
        );

        if ($this->config->negotiateLocale) {
            Services::config()->defaultLang = $this->negotiateLocale();
        }

        Services::logger()->debug('Request class loaded');
    }

    public function body(): string
    {
        file_get_contents('php://input');
    }

    public function cookie(string $key)
    {
        return filter_input(INPUT_COOKIE, $key);
    }

    public function get(string $key)
    {
        return filter_input(INPUT_GET, $key);
    }

    public function isAjax(): bool
    {
        return array_key_exists('HTTP_X_REQUESTED_WITH', $_SERVER) &&
            'xmlhttprequest' === strtolower($_SERVER['HTTP_X_REQUESTED_WITH']);
    }

    public function isCLI(): bool
    {
        return PHP_SAPI === 'cli';
    }

    public function json(bool $makeArray = false)
    {
        return json_decode($this->body(), $makeArray);
    }

    public function method(): string
    {
        return $this->server('request_method');
    }

    public function negotiateLocale(): string
    {
        if ( ! array_key_exists('HTTP_ACCEPT_LANGUAGE', $_SERVER)) {
            return $this->config->defaultLocale;
        }

        $languages = [];
        foreach (explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']) AS $language) {
            $languageData = explode(';q=', $language);
            $language = array_shift($languageData);
            $preference = array_shift($languageData) ?? 1;
            $languages[$language] = (float) $preference;
        }

        uasort(
            $languages,
            function($a, $b) {
                if ($a == $b) {
                    return 0;
                }

                return ($a > $b) ? -1 : 1;
            }
        );

        foreach ($languages AS $lang => $preference) {
            if (in_array($lang, $this->config->supportedLocales)) {
                // success
                return $lang;
            }
        }

        foreach ($languages AS $lang => $preference) {
            $lang = substr($lang, 0, 2);
            if (in_array($lang, $this->config->supportedLocales)) {
                // success
                return $lang;
            }
        }

        return $this->config->defaultLocale;
    }

    public function post(string $key)
    {
        return filter_input(INPUT_POST, $key);
    }

    public function segment(int $n): ?string
    {
        return array_key_exists($n, $this->segments) ?
            $this->segments[$n] :
            null;
    }

    public function segmentArray(): array
    {
        return $this->segments;
    }

    public function segmentCount(): int
    {
        return count($this->segments);
    }

    public function server(string $key)
    {
        return filter_input(INPUT_SERVER, strtoupper($key));
    }

    public function uriString(): string
    {
        return implode($this->segments, '/');
    }

    public function xml()
    {
        return new SimpleXMLElement(
            $this->body(),
            LIBXML_NOBLANKS | LIBXML_NOCDATA
        );
    }

}
