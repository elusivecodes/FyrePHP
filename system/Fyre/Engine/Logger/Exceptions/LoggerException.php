<?php

namespace Fyre\Engine\Logger\Exceptions;

use
    Error;

class LoggerExceptions extends Error
{

    public static function configInvalid(?string $key): self
    {
        throw new static('Logger config "'.$key.'" must be an instance of LoggerConfig');
    }

    public static function configNotExists(?string $key): self
    {
        throw new static('Logger config "'.$key.'" not found.');
    }

    public static function typeInvalid(string $type): self
    {
        throw new static('Invalid logging type specified: '.$type);
    }

}
