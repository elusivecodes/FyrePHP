<?php

namespace Config\Engine;

use
    Fyre\Engine\Response\ResponseConfig;

class Response extends ResponseConfig
{
    public $charset =  'UTF-8';

    public $compression = 1;
    public $compressionLevel = 5;

    public $csp = false;
    public $cspConfig = 'Engine\ContentSecurityPolicy';
}
