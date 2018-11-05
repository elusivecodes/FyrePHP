<?php

namespace Fyre\Engine\Request\Exceptions;

use
    Error;

class RequestExceptions extends Error
{

    public static function configInvalid(?string $key): self
    {
        throw new static('Request config "'.$key.'" must be an instance of RequestConfig');
    }

    public static function configNotExists(?string $key): self
    {
        throw new static('Request config "'.$key.'" not found.');
    }

}
