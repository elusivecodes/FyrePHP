<?php
declare(strict_types=1);

use App\Application;
use Fyre\Loader\Loader;
use Fyre\Utility\Path;

// Constants
define('TIME_START', hrtime());
define('ROOT', __DIR__);
define('APP', Path::join(ROOT, 'app'));
define('CONFIG', Path::join(ROOT, 'config'));
define('LANG', Path::join(ROOT, 'language'));
define('LOG', Path::join(ROOT, 'log'));
define('TEMPLATES', Path::join(ROOT, 'templates'));
define('TMP', Path::join(ROOT, 'tmp'));

// Register autoloader
Loader::addClassMap($composer->getClassMap());
Loader::addNamespaces($composer->getPrefixesPsr4());
Loader::register();

// Initialize application
Application::bootstrap();
Application::routes();
