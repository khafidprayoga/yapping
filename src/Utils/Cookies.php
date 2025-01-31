<?php

namespace Khafidprayoga\PhpMicrosite\Utils;

use Khafidprayoga\PhpMicrosite\Commons\AppMode;
use Khafidprayoga\PhpMicrosite\Configs\AppConfig;
use Khafidprayoga\PhpMicrosite\Configs\tokenExpired;

class Cookies
{
    public static function formatSettings(AppConfig $appConfig, int $expiresIn, string $path): array
    {
        $config = [
            'samesite' => 'Strict',
            'httponly' => true,
            'expires' => $expiresIn,
            'path' => $path,
        ];

        if ($appConfig->appMode == AppMode::PRODUCTION) {
            $config['secure'] = true;
            $config['domain'] = $appConfig->appDomain;
        }


        return $config;
    }
}
