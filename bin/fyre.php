<?php
declare(strict_types=1);

use Fyre\Command\CommandRunner;

chdir(__DIR__);

// Load application
require realpath('../autoload.php');

// Run command
$code = app()->use(CommandRunner::class)->handle($argv);

exit($code);
