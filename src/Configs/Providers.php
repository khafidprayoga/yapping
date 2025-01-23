<?php

namespace Khafidprayoga\PhpMicrosite\Configs;

class Providers
{
    public Jwt $jwt;
    public Logger $logger;

    public function __construct(Jwt $Jwt, Logger $Logger)
    {
        $this->jwt = $Jwt;
        $this->logger = $Logger;
    }
}
