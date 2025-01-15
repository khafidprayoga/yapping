<?php

namespace Khafidprayoga\PhpMicrosite\Controllers;

use Khafidprayoga\PhpMicrosite\Providers\Logger;
use Khafidprayoga\PhpMicrosite\Providers\TwigEngine;
use Monolog\Logger as MonologLogger;

use Twig\Environment;

class Main
{
    private Environment $twig;
    public MonologLogger $log;

    public function __construct()
    {
        $this->twig = TwigEngine::getInstance();
        $this->log = Logger::getInstance();
    }

    public function render(string $template, array $data = []): void
    {
        $templateName = $template . ".twig";
        $view = $this->twig->render($templateName, $data);
        echo $view;

    }
}