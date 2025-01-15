<?php

namespace Khafidprayoga\PhpMicrosite\Providers;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class TwigEngine
{
    private static ?Environment $twig = null;

    public static function getInstance(): Environment
    {
        if (self::$twig == null) {
            self::$twig = new Environment(new FilesystemLoader(APP_ROOT . "/src/Views"), []);
        }

        return self::$twig;
    }
}
