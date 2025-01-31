<?php

namespace Khafidprayoga\PhpMicrosite\Providers;

use HTMLPurifier;
use HTMLPurifier_Config;

class HtmlPurify implements ProviderInterface
{
    public static ?HtmlPurifier $htmlPurifier = null;

    public static function getInstance(): mixed
    {
        if (is_null(self::$htmlPurifier)) {
            $config = HTMLPurifier_Config::createDefault();
            $config->set('HTML.AllowedElements', ['b', 'i', 'u', 'pre']);
            $purifier = new HTMLPurifier($config);
            self::$htmlPurifier = $purifier;
        }
        return self::$htmlPurifier;
    }
}
