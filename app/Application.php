<?php
declare(strict_types=1);

namespace App;

use Fyre\Engine\Engine;
use Fyre\Error\Middleware\ErrorHandlerMiddleware;
use Fyre\Middleware\MiddlewareQueue;
use Fyre\Router\Middleware\RouterMiddleware;
use Fyre\Security\Middleware\CspMiddleware;
use Fyre\Security\Middleware\CsrfProtectionMiddleware;

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
            ->add(new ErrorHandlerMiddleware())
            ->add(new CsrfProtectionMiddleware())
            ->add(new CspMiddleware())
            ->add(new RouterMiddleware());
    }

}
