<?php

namespace Fyre\Engine\Renderer\Exceptions;

use
    Error;

class RendererException extends Error
{

    public static function configInvalid(?string $key): self
    {
        throw new static('Renderer config "'.$key.'" must be an instance of RendererConfig');
    }

    public static function configNotExists(?string $key): self
    {
        throw new static('Renderer config "'.$key.'" not found.');
    }

}
