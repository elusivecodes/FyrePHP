<?php

namespace Fyre\Engine;

use
    Config\Services;

abstract class Model extends Core
{
    protected $db;

    public function __construct(string $db = null)
    {
        $this->db = Services::database($db);
    }

}
