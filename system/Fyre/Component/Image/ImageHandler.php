<?php

namespace Fyre\Component\Image;

use
    Config\Services;

abstract class ImageHandler
{
    protected $config;

    public function __construct(ImageConfig &$config) {
        $this->config = &$config;

        Services::logger()->debug('Image class loaded');
    }

}
