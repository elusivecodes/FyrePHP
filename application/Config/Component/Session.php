<?php

namespace Config\Component;

use
    Fyre\Component\Session\SessionConfig;

class Session extends SessionConfig
{
    public $handler = "\Fyre\Component\Session\Handlers\Database";
    //public $path = BASE_PATH.'sessions';
    public $path = 'sessions';

    public $cookie = 'fyreSession';
    public $expires = 3600;
    public $refresh = 300;

    public $temporary = 0;
    public $strict = 0;
    public $cleanup = 1;
}
