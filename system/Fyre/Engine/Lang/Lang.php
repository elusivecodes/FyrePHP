<?php

namespace Fyre\Engine\Lang;

use
    Config\Services;

use const
    APP_PATH,
    FYRE_PATH;

use function
    array_key_exists,
    array_keys,
    array_map,
    file_exists,
    str_replace;

class Lang implements LangInterface
{
    private $config;
    private $langLoaded = [];
    private $lang = [];

    public function __construct(LangConfig &$config)
    {
        $this->config = &$config;

        Services::logger()->debug('Lang class loaded');
    }

    public function get(string $key, ?array $replacements = null): ?string
    {
        if ( ! array_key_exists($key, $this->lang)) {
            return null;
        }

        if ( ! $replacements) {
            return $this->lang[$key];
        }

        return str_replace(
            array_map(
                function ($value) {
                    return '{'.$value.'}';
                },
                array_keys($replacements)
            ),
            $replacements,
            $this->lang[$key]
        );
    }

    public function load(string $file): void
    {
        if ( ! array_key_exists($file, $this->langLoaded)) {
            $this->langLoaded[$file] = &$this->_load($file);
        }

        $this->lang = $this->langLoaded[$file] + $this->lang;
    }

    public function set(string $key, string $text): void
    {
        $this->lang[$key] = $text;
    }

    private function &_load(string $file): array
    {
        $path = 'Lang/'.$this->config->defaultLang.'/'.$file.'.php';

        $lang = [];

        if (file_exists(FYRE_PATH.$path)) {
            require FYRE_PATH.$path;
        }

        if (file_exists(APP_PATH.$path)) {
            require APP_PATH.$path;
        }

        return $lang;
    }

}
