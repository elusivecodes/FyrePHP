<?php
declare(strict_types=1);

namespace App;

use Fyre\Auth\Middleware\AuthenticatedMiddleware;
use Fyre\Auth\Middleware\AuthMiddleware;
use Fyre\Auth\Middleware\AuthorizedMiddleware;
use Fyre\Engine\Engine;
use Fyre\Error\ErrorHandler;
use Fyre\Middleware\MiddlewareQueue;
use Fyre\Middleware\MiddlewareRegistry;
use Fyre\Router\Middleware\RouterMiddleware;
use Fyre\Security\Middleware\CspMiddleware;
use Fyre\Security\Middleware\CsrfProtectionMiddleware;
use Fyre\Server\ClientResponse;
use Throwable;

use function json;
use function request;
use function view;

/**
 * Application
 */
abstract class Application extends Engine
{
    /**
     * Bootstrap application.
     */
    public static function bootstrap(): void
    {
        parent::bootstrap();

        ErrorHandler::setRenderer(
            function(Throwable $exception): ClientResponse|string {
                $contentType = request()->negotiate('content', ['text/html', 'application/json']);

                return match ($contentType) {
                    'application/json' => json([
                        'message' => $exception->getMessage(),
                    ]),
                    default => view('error', [
                        'exception' => $exception,
                    ])
                };
            }
        );

        MiddlewareRegistry::map('auth', AuthenticatedMiddleware::class);
        MiddlewareRegistry::map('can', AuthorizedMiddleware::class);
    }

    /**
     * Build application middleware.
     *
     * @param MiddlewareQueue $queue The MiddlewareQueue.
     * @return MiddlewareQueue The MiddlewareQueue.
     */
    public static function middleware(MiddlewareQueue $queue): MiddlewareQueue
    {
        return $queue
            ->add(new CsrfProtectionMiddleware())
            ->add(new CspMiddleware([
                'default' => [],
                'report' => [],
                'reportTo' => [],
            ]))
            ->add(new RouterMiddleware())
            ->add(new AuthMiddleware());
    }
}
