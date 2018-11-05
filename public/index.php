<?php

$config = [
    'basePath' => '../',
    'appPath' => '../application',
    'sysPath' => '../system',
    'errorLevel' => E_ALL
];

/* Start the engine.. */

require realpath($config['sysPath']).'/Fyre/Engine/Engine.php';

Fyre\Engine\Engine::run($config);
