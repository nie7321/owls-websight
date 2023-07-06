<?php

namespace App\Domains\Foundation\Exceptions;

use Exception;

class NoRollback extends Exception
{
    public function __construct()
    {
        return parent::__construct('Migration cannot be rolled back.');
    }
}
