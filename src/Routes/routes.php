<?php


use Khafidprayoga\PhpMicrosite\Commons\HttpMethod;
use Khafidprayoga\PhpMicrosite\Controllers;
use Khafidprayoga\PhpMicrosite\Routes\RouteMap;

/**
 * Define the structure for route definitions
 * @param string $method The HTTP method (lowercase)
 * @param string $path The route path (regex pattern for bramus/router)
 * @param callable|array $controller The controller, either as a closure or [ClassName, 'method']
 */
$routes = array(
    "HomePage" => new RouteMap(
        method: HttpMethod::GET,
        path: "/(index.*)?",
        handler: [Controllers\HomeController::class, 'index'],
    ),
    "GetFeedById" => new RouteMap(
        method: HttpMethod::GET,
        path: "/feeds/([0-9]+)",
        handler: [Controllers\PostController::class, 'actionGetUserById']
    ),
    "GetFeeds" => new RouteMap(
        method: HttpMethod::GET,
        path: "/feeds/",
        handler: [Controllers\PostController::class, 'index']
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
    "RevalidateSession" => new RouteMap(
        method: HttpMethod::POST,
        path: "/users/refresh_token",
        handler: [Controllers\UserController::class, 'revalidateToken']
    )
);

return $routes;
