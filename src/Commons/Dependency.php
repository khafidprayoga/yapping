<?php

namespace Khafidprayoga\PhpMicrosite\Commons;

use Khafidprayoga\PhpMicrosite\Providers\Logger;
use Monolog\Logger as MonologLogger;

class Dependency
{
    protected MonologLogger $log;

    public function __construct()
    {
        $this->log = Logger::getInstance();
    }
}
