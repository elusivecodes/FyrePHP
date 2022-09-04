<?php
declare(strict_types=1);

use
    Fyre\Cache\Cache,
    Fyre\Config\Config,
    Fyre\DB\ConnectionManager,
    Fyre\Error\ErrorHandler,
    Fyre\Log\Log,
    Fyre\Mail\Mail,
    Fyre\Queue\QueueManager,
    Fyre\Router\Router,
    Fyre\Schema\SchemaRegistry,
    Fyre\Session\Session;

ErrorHandler::register(Config::get('Error', []));

Cache::setConfig(Config::get('Cache', []));
ConnectionManager::setConfig(Config::get('Database', []));
Log::setConfig(Config::get('Log', []));
Mail::setConfig(Config::get('Mail', []));
QueueManager::setConfig(Config::get('Queue', []));
SchemaRegistry::setCache(Cache::use());
Router::setBaseUri(Config::get('App.baseUri', ''));
Session::register(Config::get('Session', []));
