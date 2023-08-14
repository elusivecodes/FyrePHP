<?php
declare(strict_types=1);

use App\Application;
use Fyre\Loader\Loader;
use Fyre\Utility\Path;

// Constants
define('TIME_START', hrtime());
define('APP', PATH::join(__DIR__, 'app'));
define('CONFIG', PATH::join(__DIR__, 'config'));
define('LANG', PATH::join(__DIR__, 'language'));
define('LOG', PATH::join(__DIR__, 'log'));
define('TEMPLATES', PATH::join(__DIR__, 'templates'));
define('TMP', PATH::join(__DIR__, 'tmp'));

// Register autoloader
Loader::addClassMap($composer->getClassMap());
Loader::addNamespaces($composer->getPrefixesPsr4());
Loader::register();

// Initialize application
Application::bootstrap();
Application::routes();
