<?php
declare(strict_types=1);

use
    Fyre\Command\CommandRunner;

chdir(__DIR__);

// Load Composer
$composer = require realpath('../vendor/autoload.php');

// Load App
require realpath('../autoload.php');

// Run Command
$code = CommandRunner::handle($argv);
exit($code);
