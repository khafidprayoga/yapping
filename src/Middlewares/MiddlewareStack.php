<?php

namespace Khafidprayoga\PhpMicrosite\Middlewares;

use Psr\Http\Message\ServerRequestInterface;

class MiddlewareStack
{
    private array $container = [];

    public function push(MiddlewareInterface $middleware): void
    {
        $this->container[] = $middleware;
    }

    public function handle(ServerRequestInterface $request): ServerRequestInterface
    {
        $next = function (ServerRequestInterface $request) {
            return $request;
        };

        $middlewares = array_reverse($this->container);
        foreach ($middlewares as $middleware) {
            $next = function (ServerRequestInterface $request) use ($middleware, $next) {
                if ($middleware instanceof MiddlewareInterface) {
                    return $middleware->invoke($request, $next);
                }
            };
        }

        return $next($request);
    }
}
