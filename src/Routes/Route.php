<?php

namespace Khafidprayoga\PhpMicrosite\Routes;

use Bramus\Router\Router;
use Khafidprayoga\PhpMicrosite\Providers\Logger;
use Monolog\Logger as MonologLogger;
use Khafidprayoga\PhpMicrosite\Views;


class Route
{
    private Router $router;
    private MonologLogger $log;

    public function __construct()
    {
        $this->router = new Router();
        $this->log = Logger::getInstance();

        $this->register();
    }

    protected function register(): void
    {
        $routes = require __DIR__ . "/routes.php";

        foreach ($routes as $route) {
            $this->router->{$route["method"]}($route["path"], $route["handler"]);
        }

        $this->router->set404(function () {
            header('HTTP/1.1 404 Not Found');
            echo Views\Fragment\NotFound::render();
        });

    }

    public function dispatch(): void
    {
        $this->router->run();
    }
}