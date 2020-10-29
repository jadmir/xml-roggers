<?php

namespace App\Exceptions;

use Exception;

class CustomException extends Exception
{
    private $errorDetail = '';

    public function __construct($message, $code, $errorDetail = '')
    {
        parent::__construct($message, $code);
        $this->errorDetail = $errorDetail;
    }

    public function getErrorDetail()
    {
        return $this->errorDetail;
    }
}
