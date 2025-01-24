<?php

namespace Khafidprayoga\PhpMicrosite\Providers;

use Monolog\Formatter\JsonFormatter;
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
            $logHandler = new ErrorLogHandler(ErrorLogHandler::OPERATING_SYSTEM, $logLevel);

            $logHandler->setFormatter(new JsonFormatter());
            self::$logger->pushHandler(
                $logHandler,
            );

        }

        return self::$logger;
    }
}
