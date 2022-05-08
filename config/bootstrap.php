<?php
declare(strict_types=1);

use
    Fyre\Cache\Cache,
    Fyre\Config\Config,
    Fyre\DB\ConnectionManager,
    Fyre\Log\Log,
    Fyre\Mail\Mail,
    Fyre\Schema\SchemaRegistry,
    Fyre\Session\Session;

Cache::setConfig(Config::get('Cache', []));
ConnectionManager::setConfig(Config::get('Database', []));
Log::setConfig(Config::get('Log', []));
Mail::setConfig(Config::get('Mail', []));
SchemaRegistry::setCache(Cache::use());
Session::register(Config::get('Session', []));
