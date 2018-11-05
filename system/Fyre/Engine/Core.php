<?php

namespace Fyre\Engine;

use
    Config\Services;

abstract class Core
{

    public function __get(string $key)
    {
        return Services::getSharedInstance()->$key;
    }

}
