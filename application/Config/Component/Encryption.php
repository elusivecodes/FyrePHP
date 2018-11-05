<?php

namespace Config\Component;

use
    Fyre\Component\Encryption\EncryptionConfig;

class Encryption extends EncryptionConfig
{
    public $handler = "\Fyre\Component\Encryption\Handlers\Sodium";
}
