<?php
declare(strict_types=1);

use
    Fyre\Cache\Handlers\FileCacher,
    Fyre\DB\Handlers\MySQL\MySQLConnection,
    Fyre\Log\Handlers\FileLogger,
    Fyre\Mail\Handlers\SmtpMailer,
    Fyre\Queue\Handlers\RedisQueue,
    Fyre\Session\Handlers\FileSessionHandler,
    Fyre\Utility\Path;

return [
    'App' => [
        'baseUri' => '',
        'charset' => 'UTF-8',
        'debug' => true
    ],
    'Cache' => [
        'default' => [
            'className' => FileCacher::class,
            'path' => Path::join(TMP, 'cache')
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
    // 'Encryption' => [],
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
