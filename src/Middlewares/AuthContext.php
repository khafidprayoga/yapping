<?php

namespace Khafidprayoga\PhpMicrosite\Middlewares;

use Khafidprayoga\PhpMicrosite\Commons\HttpException;
use Khafidprayoga\PhpMicrosite\Configs\Jwt;
use Khafidprayoga\PhpMicrosite\Controllers\InitController;
use Khafidprayoga\PhpMicrosite\Models\DTO\JwtClaimsDTO;
use Khafidprayoga\PhpMicrosite\Models\DTO\RefreshSessionRequestDTO;
use Khafidprayoga\PhpMicrosite\Utils\Cookies;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\HttpFoundation\Response;

class AuthContext extends InitController implements MiddlewareInterface
{
    private function redirectToLogin(): void
    {
        // Set the HTTP status code to 302 (temporary redirect)
        http_response_code(302);

        // Set the Location header to the login page URL
        header('Location: /signin');
        exit();
    }

    private function validate(string $accessToken, string $refreshToken): ?JwtClaimsDTO
    {
        try {
            return $this->authService->validate($accessToken);
        } catch (HttpException $e) {
            if ($e->getCode() == Response::HTTP_INTERNAL_SERVER_ERROR) {
                $req = new RefreshSessionRequestDTO(
                    ['refreshToken' => $refreshToken]
                );

                try {
                    // regenerate access token
                    $newAccessToken = $this->authService->refresh($req);

                    // set new cookies for the accessToken
                    setCookie('accessToken', $newAccessToken, Cookies::formatSettings(
                        appConfig: APP_CONFIG,
                        expiresIn: $newAccessToken->getAccessTokenExpiresAt(),
                        path: '/',
                    ));

                    // extract access token to token claims
                    return $this->authService->validate($newAccessToken);
                } catch (HttpException $e) {
                    // return null on error refreshing token (possible refresh token is expired)
                    return null;
                }
            }
            return null;
        }
    }

    public function invoke(ServerRequestInterface $request, callable $next): ServerRequestInterface
    {
        $accessToken = $_COOKIE['accessToken'] ?? null;
        $refreshToken = $_COOKIE['refreshToken'] ?? null;

        if (is_null($accessToken) && is_null($refreshToken)) {
            $this->redirectToLogin();
        }

        // decode jwt and return json err on response at server
        $claims = $this->validate($accessToken, $refreshToken);

        // if null (failed to regenerate accessToken based on refreshToken)
        if (is_null($claims)) {
            $this->redirectToLogin();
        }

        // limitation on interface, so we format back to plain array rather than typed object
        $request = $request->withAttribute("claims", JwtClaimsDTO::toArray($claims));
        $next($request);
        return $request;
    }

}
