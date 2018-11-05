<?php

namespace Fyre\Engine\Security\Exceptions;

use
    Error;

class SecurityException extends Error
{

    public static function configInvalid(?string $key): self
    {
        throw new static('Security config "'.$key.'" must be an instance of SecurityConfig');
    }

    public static function configNotExists(?string $key): self
    {
        throw new static('Security config "'.$key.'" not found.');
    }

}
