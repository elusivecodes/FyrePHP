<?php

namespace Fyre\Engine\Loader\Exceptions;

use
    Error;

class LoaderException extends Error
{

    public static function configNotExists(?string $key): self
    {
        throw new static('Loader config "'.$key.'" not found.');
    }

}
