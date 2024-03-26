<?php
declare(strict_types=1);

use App\Application;
use Fyre\Loader\Loader;
use Fyre\Utility\Path;

// Constants
define('TIME_START', hrtime());
define('ROOT', __DIR__);
define('APP', PATH::join(ROOT, 'app'));
define('CONFIG', PATH::join(ROOT, 'config'));
define('LANG', PATH::join(ROOT, 'language'));
define('LOG', PATH::join(ROOT, 'log'));
define('TEMPLATES', PATH::join(ROOT, 'templates'));
define('TMP', PATH::join(ROOT, 'tmp'));

// Register autoloader
Loader::addClassMap($composer->getClassMap());
Loader::addNamespaces($composer->getPrefixesPsr4());
Loader::register();

// Initialize application
Application::bootstrap();
Application::routes();
