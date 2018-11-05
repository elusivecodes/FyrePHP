<?php

namespace Fyre\Component\Encryption;

use
    Config\Services;

abstract class EncryptionHandler
{
    protected $config;

    public function __construct(EncryptionConfig &$config) {
        $this->config = &$config;

        Services::logger()->debug('Encryption class loaded');
    }

}
