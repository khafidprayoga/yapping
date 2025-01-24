<?php

namespace Khafidprayoga\PhpMicrosite\Routes;

use Bramus\Router\Router;
use Khafidprayoga\PhpMicrosite\Commons\Dependency;
use Khafidprayoga\PhpMicrosite\Middlewares\MiddlewareInterface;
use Khafidprayoga\PhpMicrosite\Providers\TwigEngine;
use Khafidprayoga\PhpMicrosite\Views;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7Server\ServerRequestCreator;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\HttpFoundation\Response;

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

        $psr17Factory = new Psr17Factory();
        $creator = new ServerRequestCreator(
            serverRequestFactory: $psr17Factory,
            uriFactory: $psr17Factory,
            uploadedFileFactory: $psr17Factory,
            streamFactory: $psr17Factory,
        );

        $request = $creator->fromGlobals();


        $this->log->info('New incoming HTTP Request', [
            'http_method' => $request->getMethod(),
            'uri' => $request->getUri()->getPath(),
            'ip_addr' => $request->getServerParams()['REMOTE_ADDR'],
            'user_agent' => $request->getServerParams()['HTTP_USER_AGENT'],
        ]);

        foreach ($routes as $name => $route) {
            if ($route instanceof RouteMap) {
                $middlewares = $route->getMiddlewares();

                if ($middlewares) {
                    foreach ($middlewares as $mid) {
                        $initMid = new $mid();

                        // has middleware stack and implement MiddlewareInterface
                        if ($initMid instanceof MiddlewareInterface) {
                            $httpMethod = $route->getMethod();
                            $path = $route->getPath();
                            $handler = $route->getHandler();

                            // mount hooks before actions
                            $this->router->before($httpMethod, $path, function (...$args) use ($handler, $request, $route, $initMid) {
                                $next = function (ServerRequestInterface $request) use ($handler, $args) {
                                    // Call the route handler
                                    $controller = new $handler[0]();
                                    return $controller->{$handler[1]}($request, ...$args);
                                };

                                return $initMid->invoke($request, $next);
                            });
                        } else {
                            error_log("Fatal: error mounting middleware $mid not implements MiddlewareInterface");
                            exit(1);
                        }
                    }
                } else {
                    // routes without middleware stack, so we mount handler directly
                    $this->router->{$route->getMethod()}($route->getPath(), function (...$args) use ($route, $request) {
                        $handler = $route->getHandler();

                        return (new $handler[0]())->{$handler[1]}($request, ...$args);
                    });
                }
            } else {
                error_log("Fatal: route must be instanceof " . RouteMap::class);
                exit(1);
            }
        }


        // set not found route
        $this->router->set404(function () {
            $twig = TwigEngine::getInstance();
            http_response_code(Response::HTTP_NOT_FOUND);

            echo $twig->render("Fragment/Exception.twig", [
                "error_title" => "Invalid resource",
                "error_message" => "You are not in the right place. If something is missing, please kindly check the URL.",
                "menu" => "Resources",
            ]);
        });

    }

    public function dispatch(): void
    {
        $this->router->run();
    }
}
