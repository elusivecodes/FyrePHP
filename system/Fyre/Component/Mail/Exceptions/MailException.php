<?php

namespace Fyre\Component\Mail\Exceptions;

use
    Error;

class MailException extends Error
{

    public static function noHandler(): self
    {
        throw new static('Mail config must contain a handler.');
    }

    public static function invalidHandler(string $handler): self
    {
        throw new static('Invalid mail handler specified: '.$handler);
    }

}
