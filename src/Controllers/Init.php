<?php

namespace Khafidprayoga\PhpMicrosite\Controllers;

use Khafidprayoga\PhpMicrosite\Commons\Dependency;
use Khafidprayoga\PhpMicrosite\Providers\TwigEngine;
use Twig\Environment;

class Init extends Dependency
{
    private Environment $twig;

    public function __construct()
    {
        parent::__construct();
        $this->twig = TwigEngine::getInstance();
    }

    public function render(string $template, array $data = []): void
    {
        $templateName = $template . ".twig";
        $view = $this->twig->render($templateName, $data);
        echo $view;

    }
}
