<?php
declare(strict_types=1);

use
    App\Application,
    Fyre\Loader\Loader,
    Fyre\Utility\Path;

// Constants
define('APP', PATH::join(__DIR__, 'app'));
define('CONFIG', PATH::join(__DIR__, 'config'));
define('LANG', PATH::join(__DIR__, 'language'));
define('LOG', PATH::join(__DIR__, 'log'));
define('TEMPLATES', PATH::join(__DIR__, 'templates'));
define('TMP', PATH::join(__DIR__, 'tmp'));

// Register Loader
Loader::addClassMap($composer->getClassMap());
Loader::addNamespaces([
    'App' => APP
]);
Loader::register();

// Run Application
Application::bootstrap();
Application::routes();
