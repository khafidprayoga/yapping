<?php

namespace Khafidprayoga\PhpMicrosite\Routes;

use Bramus\Router\Router;
use Khafidprayoga\PhpMicrosite\Commons\Dependency;
use Khafidprayoga\PhpMicrosite\Views;

class Route extends Dependency
{
    private Router $router;

    public function __construct()
    {
        parent::__construct();
        $this->router = new Router();

        $this->register();
    }

    protected function register(): void
    {
        $routes = require __DIR__ . "/routes.php";

        foreach ($routes as $name => $route) {
            if ($route instanceof RouteMap) {
                $this->router->{$route->getMethod()}($route->getPath(), function (...$args) use ($route) {
                    $handler = $route->getHandler();
                    return (new $handler[0]())->{$handler[1]}(...$args);
                });
                continue;
            }

            $this->router->{$route["method"]}($route["path"], $route["handler"]);
        }

        // set not found route
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
