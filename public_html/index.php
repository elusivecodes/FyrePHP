<?php
declare(strict_types=1);

use
    App\Application,
    Fyre\Middleware\MiddlewareQueue,
    Fyre\Middleware\RequestHandler,
    Fyre\Server\ServerRequest;

// Load Composer
$composer = require realpath('../vendor/autoload.php');

// Load App
require realpath('../autoload.php');

// Run Application
$queue = Application::middleware(new MiddlewareQueue);
$handler = new RequestHandler($queue);
$handler->handle(new ServerRequest)->send();