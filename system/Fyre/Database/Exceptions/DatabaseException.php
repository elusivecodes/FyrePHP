<?php

namespace Fyre\Database\Exceptions;

use
    Error;

class DatabaseException extends Error
{

    public static function noConfig(): self
    {
        throw new static('Database config not found.');
    }

    public static function noHandler(): self
    {
        throw new static('Database config must contain a handler.');
    }

    public static function invalidConfig(): self
    {
        throw new static('Database config must be an instance of DatabaseConfig');
    }

    public static function invalidHandler(string $handler): self
    {
        throw new static('Invalid database handler specified: '.$handler);
    }

}
