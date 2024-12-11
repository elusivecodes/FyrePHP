<?php
declare(strict_types=1);

use Fyre\Middleware\RequestHandler;

// Load application
require realpath('../autoload.php');

// Handle request
app()->call([RequestHandler::class, 'handle'])->send();
