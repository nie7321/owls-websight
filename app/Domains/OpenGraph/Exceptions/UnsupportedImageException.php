<?php

namespace App\Domains\OpenGraph\Exceptions;

class UnsupportedImageException extends \Exception
{
    public static function forContentType(?string $type): self
    {
        return new self("Unsupported content type: {$type}");
    }
}
