<?php

namespace Config\Engine;

use
    Fyre\Engine\Logger\LoggerConfig;

class Logger extends LoggerConfig
{
    public $threshold = 4;
    public $path = BASE_PATH.'logs';
}
