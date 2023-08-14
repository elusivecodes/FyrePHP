<?php
declare(strict_types=1);

use Fyre\Command\CommandRunner;

chdir(__DIR__);

// Load Composer
$composer = require realpath('../vendor/autoload.php');

// Load application
require realpath('../autoload.php');

// Run command
$code = CommandRunner::handle($argv);
exit($code);
