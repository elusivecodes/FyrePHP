<?php
declare(strict_types=1);

namespace App;

use Fyre\Config\Config;
use Fyre\Engine\Engine;
use Fyre\Middleware\MiddlewareQueue;

/**
 * Application
 */
class Application extends Engine
{
    /**
     * Start the Engine.
     *
     * @param Config $config The Config.
     */
    public function boot(Config $config): void
    {
        $config
            ->load('functions')
            ->load('bootstrap');
    }

    /**
     * Build application middleware.
     *
     * @param MiddlewareQueue $queue The MiddlewareQueue.
     * @return MiddlewareQueue The MiddlewareQueue.
     */
    public function middleware(MiddlewareQueue $queue): MiddlewareQueue
    {
        return $queue
            ->add('error')
            ->add('csrf')
            ->add('csp')
            ->add('auth')
            ->add('router')
            ->add('bindings');
    }
}
