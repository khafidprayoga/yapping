<?php

namespace Khafidprayoga\PhpMicrosite\Providers;

use Monolog\Handler\ErrorLogHandler;
use Monolog\Logger as MonologLogger;
use Monolog\Level;
use Khafidprayoga\PhpMicrosite\Providers\ProviderInterface;

class Logger implements ProviderInterface
{
    private static ?MonologLogger $logger = null;

    public static function getInstance(): MonologLogger
    {
        if (self::$logger == null) {
            self::$logger = new MonologLogger('X MicroSite Logger');

            self::$logger->pushHandler(
                new ErrorLogHandler(ErrorLogHandler::OPERATING_SYSTEM, Level::Debug)
            );

        }

        return self::$logger;
    }
}
