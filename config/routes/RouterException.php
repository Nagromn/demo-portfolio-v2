<?php

namespace App\Config\Routes;

use Throwable;

class RouterException extends \Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        $message = "Error: " . $message;
        parent::__construct($message, $code, $previous);
    }
}