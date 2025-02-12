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
    "DeleteFeedById" => new RouteMap(
        method: HttpMethod::PATCH,
        path: "/feeds/([0-9]+)",
        handler: [Controllers\PostController::class, 'actionDeletePostById'],
        middleware: [Middlewares\AuthContext::class]
    ),
    "GetFeeds" => new RouteMap(
        method: HttpMethod::GET,
        path: "/feeds",
        handler: [Controllers\PostController::class, 'index'],
        middleware: [Middlewares\AuthContext::class]
    ),
    "NewFeed" => new RouteMap(
        method: HttpMethod::POST,
        path: "/feeds",
        handler: [Controllers\PostController::class, 'actionNewPost'],
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
    "Logout" => new RouteMap(
        method: HttpMethod::POST,
        path: "/users/logout",
        handler: [Controllers\UserController::class, 'actionLogout'],
        middleware: [Middlewares\AuthContext::class],
    ),
    "ResetPasswordCheckpoint" => new RouteMap(
        method: HttpMethod::POST,
        path: "/users/forgot_password",
        handler: [Controllers\UserController::class, 'actionResetPassword']
    ),
    "SignIn" => new RouteMap(
        method: HttpMethod::GET,
        path: "/signin",
        handler: [Controllers\UserController::class, 'signIn']
    ),
    "SignUp" => new RouteMap(
        method: HttpMethod::GET,
        path: "/signup",
        handler: [Controllers\UserController::class, 'signUp']
    ),
    "ForgotPassword" => new RouteMap(
        method: HttpMethod::GET,
        path: "/users/forgot",
        handler: [Controllers\UserController::class, 'resetPassword']
    ),
    "RevalidateSession" => new RouteMap(
        method: HttpMethod::POST,
        path: "/users/refresh_token",
        handler: [Controllers\UserController::class, 'actionRevalidateToken']
    )
);

return $routes;
