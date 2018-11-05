<?php

namespace Config\Engine;

use
    Fyre\Engine\Security\SecurityConfig;

class Security extends SecurityConfig
{
    public $csrfProtection = 1;
    public $csrfToken = 'fyreToken';
    public $csrfCookie = 'fyreCookie';

    public $csrfExpires = 3600;
    public $csrfRegenerate = 1;
}
