<?php

namespace Khafidprayoga\PhpMicrosite\Commons;

use Exception;

class HttpException extends Exception
{
    public array $errorFields;

    public function __construct(string|array $message, int $code)
    {
        if (is_array($message)) {
            $this->errorFields = $message;
            $message = "contains fields error on request data";
        }

        parent::__construct($message, $code);
    }
}
