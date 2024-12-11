<?php
declare(strict_types=1);

use Fyre\Error\ErrorHandler;
use Fyre\Utility\Path;

// Load environment variables
if (file_exists(Path::join(CONFIG, '.env'))) {
    $env = parse_ini_file('.env');
    foreach ($env as $key => $value) {
        putenv($key.'='.$value);
    }
}

// Load application config
config()->load('app');

// Register error handler
app(ErrorHandler::class)->register();

// Set global defaults
locale_set_default(config('App.locale', 'en'));
date_default_timezone_set(config('App.timezone', 'UTC'));
mb_internal_encoding(config('App.charset', 'UTF-8'));

// Start session
session()->start();
