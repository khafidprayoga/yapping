<?php

namespace Khafidprayoga\PhpMicrosite\Controllers;

use Khafidprayoga\PhpMicrosite\Commons\Dependency;
use Khafidprayoga\PhpMicrosite\Providers\TwigEngine;
use Khafidprayoga\PhpMicrosite\Services\UserService;
use Khafidprayoga\PhpMicrosite\Services\PostService;
use Khafidprayoga\PhpMicrosite\UseCases\UserServiceImpl;
use Twig\Environment;

class Init extends Dependency
{
    private Environment $twig;
    protected UserService $userService;
//    protected PostService $postService;

    public function __construct()
    {
        parent::__construct();
        $this->twig = TwigEngine::getInstance();
        $this->userService = new UserServiceImpl();
    }

    public function render(string $template, array $data = []): void
    {
        $templateName = $template . ".twig";
        $view = $this->twig->render($templateName, $data);
        echo $view;

    }
}
