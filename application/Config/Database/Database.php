<?php

namespace Config\Database;

use
    Fyre\Database\DatabaseConfig;

class Database extends DatabaseConfig {
    public $handler = "\Fyre\Database\Handlers\MySQLi";

    public $username = '';
    public $password = '';
    public $database = '';
    public $hostname = 'localhost';
    public $port = '3301';

    public $charset = 'utf8mb4';
    public $collation = 'utf8mb4_unicode_ci';
    public $persistent = false;
    public $compress = true;
    public $ssl = false;
}
