<?php


use Khafidprayoga\PhpMicrosite\Commons\HttpMethod;
use Khafidprayoga\PhpMicrosite\Controllers;
use Khafidprayoga\PhpMicrosite\Routes\RouteMap;
use Khafidprayoga\PhpMicrosite\Middlewares;

/**
 * Define the structure for route definitions
 * @param string $method The HTTP method (lowercase)
 * @param string $path The route path (regex pattern for bramus/router)
 * @param callable|array $controller The controller, either as a closure or [ClassName, 'method']
 * @param callable|array $middleware The controller, either as a closure or [ClassName, 'method']
 */
$routes = array(
    "HomePage" => new RouteMap(
        method: HttpMethod::GET,
        path: "/(index.*)?",
        handler: [Controllers\HomeController::class, 'index'],
        middleware: [Middlewares\AuthContext::class]
    ),
    "GetFeedById" => new RouteMap(
        method: HttpMethod::GET,
        path: "/feeds/([0-9]+)",
        handler: [Controllers\PostController::class, 'actionGetPostById'],
        middleware: [Middlewares\AuthContext::class]
    ),
    "GetFeeds" => new RouteMap(
        method: HttpMethod::GET,
        path: "/feeds/",
        handler: [Controllers\PostController::class, 'index'],
        middleware: [Middlewares\AuthContext::class]
    ),
    "CreateUser" => new RouteMap(
        method: HttpMethod::POST,
        path: "/users/register",
        handler: [Controllers\UserController::class, 'actionCreateUser']
    ),
    "Login" => new RouteMap(
        method: HttpMethod::POST,
        path: "/users/login",
        handler: [Controllers\UserController::class, 'actionAuthenticate']
    ),
    "SignIn" => new RouteMap(
        method: HttpMethod::GET,
        path: "/signin",
        handler: [Controllers\UserController::class, 'signIn']
    ),
    "SignUp"=>new RouteMap(
        method: HttpMethod::GET,
        path: "/signup",
        handler: [Controllers\UserController::class, 'signUp']
    ),
    "RevalidateSession" => new RouteMap(
        method: HttpMethod::POST,
        path: "/users/refresh_token",
        handler: [Controllers\UserController::class, 'actionRevalidateToken']
    )
);

return $routes;
