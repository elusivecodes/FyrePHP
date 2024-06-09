<?php
declare(strict_types=1);

use App\Application;
use Fyre\Middleware\MiddlewareQueue;
use Fyre\Middleware\RequestHandler;
use Fyre\Server\ServerRequest;

// Load Composer
$composer = require realpath('../vendor/autoload.php');

// Load application
require realpath('../autoload.php');

// Handle request
$queue = Application::middleware(new MiddlewareQueue());
$handler = new RequestHandler($queue);
$handler->handle(ServerRequest::instance())->send();