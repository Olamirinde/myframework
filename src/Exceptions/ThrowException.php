<?php

namespace App\Exceptions;

use Exception;

class ThrowException extends Exception
{
    public $errors;

    public function __construct($code, $message = '', $errors = null)
    {
        parent::__construct($message);
        $this->code   = $code;
        $this->errors = $errors;
    }

    public static function notFound($message = 'Resource not found')
    {
        return new static(404, $message);
    }

    public static function validation($errors, $message = 'Validation failed')
    {
        return new static(400, $message, $errors);
    }

    public static function unauthorized($message = 'Unauthorized')
    {
        return new static(400, $message);
    }
}