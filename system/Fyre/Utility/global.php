<?php

use
    Config\Services;

if ( ! function_exists('config')) {
    function config(?string $key = null)
    {
        if ($key === null) {
            return Services::config();
        }

        return Services::config()->$key;
    }
}

if ( ! function_exists('instance')) {
    function instance(): ?object
    {
        return Services::getSharedInstance();
    }
}

if ( ! function_exists('lang')) {
    function lang(?string $key = null, ...$args): ?string
    {
        if ($key === null) {
            return Services::lang();
        }

        return Services::lang()->get($key, ...$args);
    }
}

if ( ! function_exists('log_message')) {
    function log_message(string $type, string $message)
    {
        return Config\Services::logger()->logMessage($type, $message);
    }
}

if ( ! function_exists('service')) {
    function service($key, ...$args)
    {
        return Config\Services::$key(...$args);
    }
}

if ( ! function_exists('site_url')) {
    function site_url(string $path = ''): string
    {
        return rtrim(
            config('baseUrl'),
            '/'
        ).
        ($path ?
            '/'.$path :
            ''
        );
    }
}

if ( ! function_exists('show_error')) {
    function show_error(string $message, int $statusCode = 500)
    {
        Config\Services::response()
            ->reset()
            ->setStatusCode($statusCode)
            ->view(
                'errors/error',
                [
                    'message' => $message
                ]
            )
            ->_render();
        exit;
    }
}

if ( ! function_exists('show_404')) {
    function show_404()
    {
        Config\Services::response()
            ->reset()
            ->setStatusCode(404)
            ->view('errors/404')
            ->_render();
        exit;
    }
}

if ( ! function_exists('utility')) {
    function utility($file)
    {
        static $loaded = [];

        if (is_array($file)) {
            return array_map('utility', $file);
        }

        if (in_array($file, $loaded)) {
            return;
        }

        if (file_exists(APP_PATH.'Utility/'.$file.'.php')) {
            include APP_PATH.'Utility/'.$file.'.php';
        }

        if (file_exists(FYRE_PATH.'Utility/'.$file.'.php')) {
            include FYRE_PATH.'Utility/'.$file.'.php';
        }

        $loaded[] = $file;
    }
}
