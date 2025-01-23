<?php

namespace Khafidprayoga\PhpMicrosite\Commons;

use Exception;

class HttpException extends Exception
{
    public function __construct(string $message, int $code)
    {
        parent::__construct($message, $code);
    }
}
