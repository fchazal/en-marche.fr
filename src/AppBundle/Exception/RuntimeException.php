<?php

namespace AppBundle\Exception;

class RuntimeException extends \RuntimeException
{
    public function __construct($message = '', \Exception $previous = null, $code = 0)
    {
        parent::__construct($message, $code, $previous);
    }
}
