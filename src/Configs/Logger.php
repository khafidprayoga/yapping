<?php

namespace Khafidprayoga\PhpMicrosite\Configs;

use Monolog\Level;

class Logger
{
    public string $level;

    public function __construct(string $level)
    {
        $this->level = $level;
    }
}
