<?php

namespace Khafidprayoga\PhpMicrosite\Controllers;

use Khafidprayoga\PhpMicrosite\Providers\Logger;
use Monolog\Logger as MonologLogger;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class Main
{
    private Environment $twig;
    public MonologLogger $log;

    public function __construct()
    {
        $twig = new Environment(new FilesystemLoader(APP_ROOT . "/src/Views"), []);


        $this->twig = $twig;
        $this->log = Logger::getInstance();

    }

    public function render(string $template, array $data = []): void
    {
        $view = $this->twig->render($template, $data);
        echo $view;

    }
}