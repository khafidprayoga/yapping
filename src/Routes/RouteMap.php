<?php

namespace Khafidprayoga\PhpMicrosite\Routes;

use Khafidprayoga\PhpMicrosite\Commons\HttpMethod;

readonly class RouteMap
{
    public function __construct(
        public HttpMethod $method,
        public string     $path,
        public array      $handler,
        public ?array      $middleware = [],
    ) {
    }

    public function getMethod(): string
    {
        return $this->method->value;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getHandler(): array
    {
        return $this->handler;
    }
    public function getMiddlewares(): array
    {
        return $this->middleware;
    }
}
