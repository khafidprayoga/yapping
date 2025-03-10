<?php

namespace Khafidprayoga\PhpMicrosite\Providers;

use Khafidprayoga\PhpMicrosite\Commons\TwigCarbonExtension;
use Khafidprayoga\PhpMicrosite\Configs\AppConfig;
use Twig\Environment;
use Twig\Extension\CoreExtension;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;

class TwigEngine implements ProviderInterface
{
    private static ?Environment $twig = null;

    public static function getInstance(): Environment
    {
        if (self::$twig == null) {
            self::$twig = new Environment(new FilesystemLoader(APP_ROOT . "/src/Views"), []);
            self::$twig->addExtension(new DebugExtension());
            self::$twig->getExtension(CoreExtension::class)
                ->setTimezone(APP_CONFIG->serverTimeZone);

            self::$twig->getExtension(CoreExtension::class)
                ->setDateFormat("Y-m-d H:i:s");
            self::$twig->addExtension(new TwigCarbonExtension());

        }

        return self::$twig;
    }
}
