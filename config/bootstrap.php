<?php
declare(strict_types=1);

use Fyre\Cache\Cache;
use Fyre\Config\Config;
use Fyre\DB\ConnectionManager;
use Fyre\Error\ErrorHandler;
use Fyre\Log\Log;
use Fyre\Mail\Mail;
use Fyre\Queue\QueueManager;
use Fyre\Router\Router;
use Fyre\Schema\SchemaRegistry;
use Fyre\Session\Session;

Config::load('app');

ErrorHandler::register(Config::get('Error', []));

locale_set_default(Config::get('App.locale', 'en'));
date_default_timezone_set(Config::get('App.timezone', 'UTC'));
mb_internal_encoding(Config::get('App.encoding', 'UTF-8'));

if (PHP_SAPI === 'cli') {
    Config::set('Log.default.suffix', '-cli');
}

if (Config::get('App.debug')) {
    Cache::disable();
}

Cache::setConfig(Config::get('Cache', []));
ConnectionManager::setConfig(Config::get('Database', []));
Log::setConfig(Config::get('Log', []));
Mail::setConfig(Config::get('Mail', []));
QueueManager::setConfig(Config::get('Queue', []));

Router::setBaseUri(Config::get('App.baseUri', ''));
SchemaRegistry::setCache(Cache::use('schema'));
Session::register(Config::get('Session', []));
