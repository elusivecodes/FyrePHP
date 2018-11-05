<?php

namespace Config;

use
    Fyre\Engine\Lang\LangConfig;

class Lang extends LangConfig
{
    public function __construct() {
        $this->defaultLang = Services::config()->defaultLang;
    }
}
