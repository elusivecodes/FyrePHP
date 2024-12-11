<?php
declare(strict_types=1);

use Fyre\Auth\Authenticators\SessionAuthenticator;
use Fyre\Cache\Handlers\FileCacher;
use Fyre\DB\Handlers\MySQL\MySQLConnection;
use Fyre\Log\Handlers\FileLogger;
use Fyre\Mail\Handlers\SmtpMailer;
use Fyre\Queue\Handlers\RedisQueue;
use Fyre\Server\ClientResponse;
use Fyre\Session\Handlers\FileSessionHandler;
use Fyre\Utility\Path;

return [
    'App' => [
        'baseUri' => env('BASE_URI', ''),
        'charset' => 'UTF-8',
        'debug' => true,
        'defaultLayout' => null,
        'locale' => 'en',
        'timezone' => 'UTC',
    ],
    'Auth' => [
        'authenticators' => [
            [
                'className' => SessionAuthenticator::class,
            ],
        ],
    ],
    'Cache' => [
        'default' => [
            'className' => FileCacher::class,
            'path' => Path::join(TMP, 'cache'),
        ],
        'schema' => [
            'className' => FileCacher::class,
            'path' => Path::join(TMP, 'schema'),
        ],
    ],
    'Csrf' => [
        'salt' => '{salt}',
    ],
    'Database' => [
        'default' => [
            'className' => MySQLConnection::class,
            'host' => env('DB_HOST', '127.0.0.1'),
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),
            'database' => env('DB_NAME', ''),
            'port' => (int) env('DB_PORT', '3306'),
            'collation' => 'utf8mb4_unicode_ci',
            'charset' => 'utf8mb4',
        ],
    ],
    'Error' => [
        'level' => E_ALL,
        'log' => true,
        'renderer' => function(Throwable $exception): ClientResponse|string {
            $contentType = request()->negotiate('content', ['text/html', 'application/json']);

            return match ($contentType) {
                'application/json' => json([
                    'message' => $exception->getMessage(),
                ]),
                default => view('error', [
                    'exception' => $exception,
                ])
            };
        },
    ],
    'Log' => [
        'default' => [
            'className' => FileLogger::class,
            'path' => LOG,
            'threshold' => 5,
        ],
    ],
    'Mail' => [
        'default' => [
            'className' => SmtpMailer::class,
            'host' => env('SMTP_HOST', ''),
            'username' => env('SMTP_USERNAME', ''),
            'password' => env('SMTP_PASSWORD', ''),
            'port' => (int) env('SMTP_PORT', '587'),
            'auth' => filter_var(env('SMTP_AUTH', '1'), FILTER_VALIDATE_BOOLEAN),
            'tls' => filter_var(env('SMTP_TLS', '1'), FILTER_VALIDATE_BOOLEAN),
        ],
    ],
    'Queue' => [
        'default' => [
            'className' => RedisQueue::class,
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'password' => env('REDIS_PASSWORD', ''),
            'port' => (int) env('REDIS_PORT', '6379'),
        ],
    ],
    'Session' => [
        'handler' => [
            'className' => FileSessionHandler::class,
        ],
        'path' => Path::join(TMP, 'sessions'),
    ],
];
