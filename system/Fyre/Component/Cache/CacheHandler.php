<?php

namespace Fyre\Component\Cache;

use
    Config\Services;

abstract class CacheHandler
{
    protected $config;

    public function __construct(CacheConfig &$config)
    {
        $this->config = &$config;

        Services::logger()->debug('Cache class loaded');
    }

}
