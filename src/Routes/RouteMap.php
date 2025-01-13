<?php

namespace Khafidprayoga\PhpMicrosite\Routes;

enum HttpMethod: string
{
    case GET = 'get';
    case POST = 'post';
    case PUT = 'put';
    case PATCH = 'delete';

}

class RouteMap
{
    public function __construct(
        public readonly HttpMethod $method,
        public readonly string     $path,
        public readonly array      $handler,
    )
    {
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