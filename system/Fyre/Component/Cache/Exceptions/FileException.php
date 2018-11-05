<?php

namespace Fyre\Component\Cache\Exceptions;

use
    Error;

class FileException extends Error
{

    public static function noPath(): self
    {
        throw new static('File cache config must contain a path.');
    }

}
