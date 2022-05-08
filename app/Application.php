<?php
declare(strict_types=1);

namespace App;

use
    Fyre\CSP\Middleware\CspMiddleware,
    Fyre\CSRF\Middleware\CsrfProtectionMiddleware,
    Fyre\Engine\Engine,
    Fyre\Error\Middleware\ErrorHandlerMiddleware,
    Fyre\Middleware\MiddlewareQueue,
    Fyre\Router\Middleware\RouterMiddleware;

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
            ->add(new ErrorHandlerMiddleware)
            ->add(new CsrfProtectionMiddleware)
            ->add(new CspMiddleware)
            ->add(new RouterMiddleware);
    }

}
