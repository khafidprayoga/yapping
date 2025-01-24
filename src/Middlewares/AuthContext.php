<?php

namespace Khafidprayoga\PhpMicrosite\Middlewares;

use Khafidprayoga\PhpMicrosite\Commons\HttpException;
use Khafidprayoga\PhpMicrosite\Configs\Jwt;
use Khafidprayoga\PhpMicrosite\Controllers\InitController;
use Khafidprayoga\PhpMicrosite\Models\DTO\JwtClaimsDTO;
use Psr\Http\Message\ServerRequestInterface;

class AuthContext extends InitController implements MiddlewareInterface
{
    public function invoke(ServerRequestInterface $request, callable $next): ServerRequestInterface
    {
        try {
            $authorizations = $request->getHeader("Authorization");
            if (count($authorizations) === 0) {
                // Set the HTTP status code to 302 (temporary redirect)
                http_response_code(302);

                // Set the Location header to the login page URL
                header('Location: /users/login');
                exit(0);
            }

            $token = str_replace("Bearer ", "", $authorizations[0]);

            // decode jwt and return json err on response at server
            $claims = $this->authService->validate($token);
            // limitation on interface, so we format back to plain array rather than typed object
            $request = $request->withAttribute("claims", JwtClaimsDTO::toArray($claims));
            $next($request);
            return $request;
        } catch (HttpException $e) {
            $this->log->warning($e->getMessage());
            $this->responseJson($e, null);
            exit(1);
        }
    }
}
