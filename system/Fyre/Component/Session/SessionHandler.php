<?php

namespace Fyre\Component\Session;

abstract class SessionHandler
{
    protected $config;

    public function __construct(SessionConfig &$config)
    {
        $this->config = &$config;
    }

}
