<?php

namespace Fyre\Component\Cache\Exceptins;

use
    Error;

class OpenSSLException extends Error
{

    public static function noMode(): self
    {
        throw new static('OpenSSL Encryption config must contain a mode.');
    }

}
