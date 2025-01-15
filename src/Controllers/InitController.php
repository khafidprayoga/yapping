<?php

namespace Khafidprayoga\PhpMicrosite\Controllers;

use Khafidprayoga\PhpMicrosite\Commons\Dependency;
use Khafidprayoga\PhpMicrosite\Providers\Database;
use Khafidprayoga\PhpMicrosite\Providers\TwigEngine;
use Khafidprayoga\PhpMicrosite\Services\UserService;
use Khafidprayoga\PhpMicrosite\Services\PostService;
use Khafidprayoga\PhpMicrosite\UseCases\PostServiceImpl;
use Khafidprayoga\PhpMicrosite\UseCases\UserServiceImpl;
use Twig\Environment;

class InitController extends Dependency
{
    private Environment $twig;
    protected UserService $userService;

    protected PostService $postService;

    public function __construct()
    {
        parent::__construct();

        // providers
        $twig = TwigEngine::getInstance();
        $db = Database::getInstance();
        $entityManager = Database::getEntityManager();

        // mount dependency
        $this->twig = $twig;
        $this->userService = new UserServiceImpl($db, $entityManager);
        $this->postService = new PostServiceImpl($db, $entityManager);

    }

    public function render(string $template, array $data = []): void
    {
        $templateName = $template . ".twig";
        $view = $this->twig->render($templateName, $data);
        echo $view;

    }
}
