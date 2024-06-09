<?php
declare(strict_types=1);

use Fyre\Cache\Handlers\FileCacher;
use Fyre\DB\Handlers\MySQL\MySQLConnection;
use Fyre\Log\Handlers\FileLogger;
use Fyre\Mail\Handlers\SmtpMailer;
use Fyre\Queue\Handlers\RedisQueue;
use Fyre\Session\Handlers\FileSessionHandler;
use Fyre\Utility\Path;

return [
    'App' => [
        'baseUri' => '',
        'debug' => true,
        'defaultLayout' => null,
        'encoding' => 'UTF-8',
        'locale' => 'en',
        'timezone' => 'UTC'
    ],
    'Cache' => [
        'default' => [
            'className' => FileCacher::class,
            'path' => Path::join(TMP, 'cache')
        ],
        'schema' => [
            'className' => FileCacher::class,
            'path' => Path::join(TMP, 'schema')
        ]
    ],
    'Database' => [
        'default' => [
            'className' => MySQLConnection::class,
            'host' => '127.0.0.1',
            'username' => 'root',
            'password' => '',
            'database' => '',
            'port' => '3306',
            'collation' => 'utf8mb4_unicode_ci',
            'charset' => 'utf8mb4'
        ]
    ],
    'Error' => [
        'level' => E_ALL,
        'log' => true
    ],
    'Log' => [
        'default' => [
            'className' => FileLogger::class,
            'path' => LOG,
            'threshold' => 5
        ]
    ],
    'Mail' => [
        'default' => [
            'className' => SmtpMailer::class,
            'host' => '127.0.0.1',
            'username' => null,
            'password' => null,
            'port' => '465',
            'auth' => false,
            'tls' => false
        ]
    ],
    'Queue' => [
        'default' => [
            'className' => RedisQueue::class
        ]
    ],
    'Session' => [
        'className' => FileSessionHandler::class,
        'path' => Path::join(TMP, 'sessions')
    ]
];
