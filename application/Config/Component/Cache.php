<?php

namespace Config\Component;

use
    Fyre\Component\Cache\CacheConfig;

class Cache extends CacheConfig
{
    public $handler = "\Fyre\Component\Cache\Handlers\File";

    public $path = BASE_PATH.'cache';
}
