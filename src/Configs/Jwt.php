<?php

namespace Khafidprayoga\PhpMicrosite\Configs;

class Jwt
{
    public string $secretKey;

    public function __construct(string $secretKey)
    {
        $this->secretKey = $secretKey;
    }
}
