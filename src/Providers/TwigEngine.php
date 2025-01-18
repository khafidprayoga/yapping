<?php

namespace Khafidprayoga\PhpMicrosite\Providers;

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
                ->setTimezone("Asia/Jakarta");

            self::$twig->getExtension(CoreExtension::class)
                ->setDateFormat("Y-m-d H:i:s");
            ;

        }

        return self::$twig;
    }
}
