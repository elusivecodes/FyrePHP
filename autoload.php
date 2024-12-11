<?php
declare(strict_types=1);

use App\Application;
use Fyre\Loader\Loader;
use Fyre\Utility\Path;

define('TIME_START', hrtime());

// Load Composer
$composer = require realpath(__DIR__.'/vendor/autoload.php');

// Register autoloader
$loader = (new Loader())
    ->addClassMap($composer->getClassMap())
    ->addNamespaces($composer->getPrefixesPsr4())
    ->register();

// Constants
define('ROOT', __DIR__);
define('APP', Path::join(ROOT, 'app'));
define('CONFIG', Path::join(ROOT, 'config'));
define('LANG', Path::join(ROOT, 'language'));
define('LOG', Path::join(ROOT, 'log'));
define('TEMPLATES', Path::join(ROOT, 'templates'));
define('TMP', Path::join(ROOT, 'tmp'));

// Boot application
$app = new Application($loader);
Application::setInstance($app);
$app->call([$app, 'boot']);
