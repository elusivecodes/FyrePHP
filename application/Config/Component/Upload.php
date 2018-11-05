<?php

namespace Config\Component;

use
    Fyre\Component\Upload\UploadConfig;

class Upload extends UploadConfig
{
    public $path = BASE_PATH.'public/uploads';
    public $allowedTypes = ['jpg', 'png', 'gif'];

    public $minSize = 0;
    public $maxSize = 0;

    public $minWidth = 0;
    public $maxWidth = 0;
    public $minHeight = 0;
    public $maxHeight = 0;

    public $encryptName = 0;
    public $overwrite = 0;
    public $maxFilenameIncrement = 100;

}
