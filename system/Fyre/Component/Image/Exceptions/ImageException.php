<?php

namespace Fyre\Component\Image\Exceptions;

use
    Error;

class ImageException extends Error
{

    public static function configInvalid(?string $key): self
    {
        throw new static('Image config "'.$key.'" must be an instance of ImageConfig');
    }

    public static function configMissing(?string $key): self
    {
        throw new static('Image config "'.$key.'" not found.');
    }

    public static function handlerInvalid(?string $key, ?string $handler): self
    {
        throw new static('Image config "'.$key.'" must contain a valid handler: '.$handler);
    }

    public static function handlerMissing(?string $key): self
    {
        throw new static('Image config "'.$key.'" must contain a handler.');
    }

    public static function handlerNotExists(?string $key, ?string $className): self
    {
        throw new static('Image config "'.$key.'" handler class not found: '.$className);
    }

}
