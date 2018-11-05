<?php

namespace Fyre\Engine\Benchmark\Exceptions;

use
    Error;

class BenchmarkException extends Error
{

    public static function configInvalid(?string $key): self
    {
        throw new static('Benchmark config "'.$key.'" must be an instance of BenchmarkConfig');
    }

    public static function configNotExists(?string $key): self
    {
        throw new static('Benchmark config "'.$key.'" not found.');
    }

}
