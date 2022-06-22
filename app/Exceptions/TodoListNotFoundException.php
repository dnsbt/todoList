<?php

namespace App\Exceptions;

use Throwable;

class TodoListNotFoundException extends \Exception
{
    public function __construct($message = "todo list not found", $code = 404, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
