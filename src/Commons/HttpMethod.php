<?php

namespace Khafidprayoga\PhpMicrosite\Commons;

use Symfony\Component\HttpFoundation\Request;

enum HttpMethod: string
{
    case GET = Request::METHOD_GET;
    case POST = Request::METHOD_POST;
    case PUT = Request::METHOD_PUT;
    case PATCH = Request::METHOD_PATCH;

}
