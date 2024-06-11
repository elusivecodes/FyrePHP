<?php
declare(strict_types=1);

namespace App;

use Fyre\Engine\Engine;
use Fyre\Error\ErrorHandler;
use Fyre\Middleware\MiddlewareQueue;
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

        ErrorHandler::setRenderer(fn(Throwable $exception): ClientResponse|string =>
            match (request()->negotiate('content', ['text/html', 'application/json'])) {
                'application/json' => json([
                    'message'=> $exception->getMessage()
                ]),
                default => view('error', [
                    'exception' => $exception
                ])
            }
        );
    }

    /**
     * Build application middleware.
     * @param MiddlewareQueue $queue The MiddlewareQueue.
     * @return MiddlewareQueue The MiddlewareQueue.
     */
    public static function middleware(MiddlewareQueue $queue): MiddlewareQueue
    {
        return $queue
            ->add(CsrfProtectionMiddleware::class)
            ->add(CspMiddleware::class)
            ->add(RouterMiddleware::class);
    }

}
