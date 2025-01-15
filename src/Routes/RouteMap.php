<?php

namespace Khafidprayoga\PhpMicrosite\Routes;

use Khafidprayoga\PhpMicrosite\Commons\HttpMethod;

class RouteMap
{
    public function __construct(
        public readonly HttpMethod $method,
        public readonly string     $path,
        public readonly array      $handler,
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
}
