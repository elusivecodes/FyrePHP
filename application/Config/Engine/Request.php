<?php

namespace Config\Engine;

use
    Fyre\Engine\Request\RequestConfig;

class Request extends RequestConfig
{
    public $negotiateLocale = true;

    public $defaultLocale = 'en';
    public $supportedLocales = ['en'];
}
