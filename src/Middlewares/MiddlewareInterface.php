<?php

namespace Khafidprayoga\PhpMicrosite\Middlewares;

use Psr\Http\Message\ServerRequestInterface;

interface MiddlewareInterface
{
    public function invoke(ServerRequestInterface $request, callable $next): ServerRequestInterface;
}
