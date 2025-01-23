<?php

namespace Khafidprayoga\PhpMicrosite\Configs;

use DateTimeZone;
use Khafidprayoga\PhpMicrosite\Commons\AppMode;

class AppConfig
{
    public string $appName;
    public AppMode $appMode;
    public Providers $providers;
    public string $serverTimeZone;
    public function __construct(
        string    $appName,
        string    $appMode,
        Providers $Providers,
        string    $serverTimeZone
    ) {
        $this->appName = $appName;

        $appModeCfg = strtolower($appMode);
        $this->appMode = match ($appModeCfg) {
            'production' => AppMode::PRODUCTION,
            default => AppMode::DEVELOPMENT,
        };

        $isValidtimeZone = in_array($serverTimeZone, DateTimeZone::listIdentifiers());
        if (!$isValidtimeZone) {
            error_log("Server time zone config is not valid");
            exit(1);
        }

        $this->serverTimeZone = $serverTimeZone;
        $this->providers = $Providers;
    }
}
