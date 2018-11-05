<?php

namespace Config\Component;

use
    Fyre\Component\Image\ImageConfig;

class Image extends ImageConfig
{
    public $handler = "\Fyre\Component\Image\Handlers\GD";
}
