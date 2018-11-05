<?php

namespace Fyre\Engine\Router\Exceptions;

use
    Error;

class RouterException extends Error
{

    public static function classNotExists(string $className): self
    {
        throw new static('Route class not found: '.$className);
    }

    public static function configNotExists(string $path): self
    {
        throw new static('Router config file does not exist: '.$path);
    }

    public static function methodNotExists(string $className, $method): self
    {
        throw new static('Route method not found: '.$className.'->'.$method);
    }

}
