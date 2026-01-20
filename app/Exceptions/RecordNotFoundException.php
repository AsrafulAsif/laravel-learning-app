<?php

namespace App\Exceptions;

use Exception;

class RecordNotFoundException extends Exception
{
    protected $code = 404;
}
