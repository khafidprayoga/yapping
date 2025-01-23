<?php

namespace Khafidprayoga\PhpMicrosite\Providers;

use Monolog\Handler\ErrorLogHandler;
use Monolog\Logger as MonologLogger;
use Monolog\Level;

class Logger implements ProviderInterface
{
    private static ?MonologLogger $logger = null;

    public static function getInstance(): MonologLogger
    {
        if (self::$logger == null) {
            self::$logger = new MonologLogger('X MicroSite Logger');

            $logLevel = Level::fromName(APP_CONFIG->providers->logger->level);
            self::$logger->pushHandler(
                new ErrorLogHandler(ErrorLogHandler::OPERATING_SYSTEM, $logLevel)
            );

        }

        return self::$logger;
    }
}
