<?php

namespace Fyre\Component\Session\Exceptions;

use
    Error;

class SessionException extends Error
{

    public static function noConfig(): self
    {
        throw new static('Session config not found.');
    }

    public static function noHandler(): self
    {
        throw new static('Session config must contain a handler.');
    }

    public static function invalidConfig(): self
    {
        throw new static('Session config must be an instance of SessionConfig');
    }

    public static function invalidHandler(string $handler): self
    {
        throw new static('Invalid session handler specified: '.$handler);
    }

}
