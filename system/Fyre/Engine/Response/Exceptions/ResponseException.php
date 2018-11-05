<?php

namespace Fyre\Engine\Response\Exceptions;

use
    Error;

class ResponseException extends Error
{

    public static function configInvalid(?string $key): self
    {
        throw new static('Response config "'.$key.'" must be an instance of ResponseConfig');
    }

    public static function configNotExists(?string $key): self
    {
        throw new static('Response config "'.$key.'" not found.');
    }

    public static function viewNotExists(string $path): self
    {
        throw new static('View file does not exist: '.$path);
    }

}
