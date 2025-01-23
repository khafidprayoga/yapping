<?php

namespace Khafidprayoga\PhpMicrosite\Configs;

use Khafidprayoga\PhpMicrosite\Commons\AppMode;

class AppConfig
{
    public string $appName;
    public AppMode $appMode;
    public Providers $providers;

    public function __construct(
        string    $appName,
        string    $appMode,
        Providers $Providers
    ) {
        $this->appName = $appName;

        $appModeCfg = strtolower($appMode);
        $this->appMode = match ($appModeCfg) {
            'production' => AppMode::PRODUCTION,
            default => AppMode::DEVELOPMENT,
        };

        $this->providers = $Providers;
    }
}
