<?php
declare(strict_types=1);

namespace App;

use Fyre\Engine\Engine;
use Fyre\Error\ErrorHandler;
use Fyre\Error\Middleware\ErrorHandlerMiddleware;
use Fyre\Middleware\MiddlewareQueue;
use Fyre\Router\Middleware\RouterMiddleware;
use Fyre\Router\Router;
use Fyre\Security\Middleware\CspMiddleware;
use Fyre\Security\Middleware\CsrfProtectionMiddleware;

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
    }

    /**
     * Build application middleware.
     * @param MiddlewareQueue $queue The MiddlewareQueue.
     * @return MiddlewareQueue The MiddlewareQueue.
     */
    public static function middleware(MiddlewareQueue $queue): MiddlewareQueue
    {
        return $queue
            ->add(ErrorHandlerMiddleware::class)
            ->add(CsrfProtectionMiddleware::class)
            ->add(CspMiddleware::class)
            ->add(RouterMiddleware::class);
    }

    /**
     * Build application routes.
     */
    public static function routes(): void
    {
        parent::routes();

        Router::setErrorRoute(fn(): string => view('error', [
            'exception' => ErrorHandler::getException()
        ]));
    }

}
