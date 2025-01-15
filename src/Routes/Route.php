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

        // server assets
        $this->router->get("styles.css", function () {
            $filePath = APP_ROOT . "/src/Views/styles.css"; // Corrected path if needed

            if (file_exists($filePath)) {
                $content = file_get_contents($filePath);
                header('Content-Type: text/css');
                header('Content-Length: ' . filesize($filePath));


                echo $content;

            } else {
                header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
                echo "CSS file not found.";
                exit;
            }

        });

        foreach ($routes as $name => $route) {
            if ($route instanceof RouteMap) {
                $this->router->{$route->getMethod()}($route->getPath(), function () use ($route) {
                    $handler = $route->getHandler();
                    return (new $handler[0]())->{$handler[1]}();
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
