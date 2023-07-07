<?php

namespace App\Domains\FediBot\Exceptions;

use Exception;

class UnknownBackend extends Exception
{
    public static function for(string $backend): self
    {
        return new self("Unknown backend '{$backend}' specified");
    }
}
