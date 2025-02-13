<?php
declare(strict_types=1);

use Fyre\Middleware\RequestHandler;
use Fyre\Server\ServerRequest;

// Load application
require realpath('../autoload.php');

// Handle request
$app = app();

$response = $app->call([RequestHandler::class, 'handle']);
$request = $app->use(ServerRequest::class);

$response->send();

$app->dispatchEvent('Engine.shutdown', [
    'request' => $request,
    'response' => $response,
]);
