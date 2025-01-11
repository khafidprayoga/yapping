<?php


use Khafidprayoga\PhpMicrosite\Views;

/**
 * Define the structure for route definitions
 * @param string $method The HTTP method (lowercase)
 * @param string $path The route path (regex pattern for bramus/router)
 * @param callable|array $controller The controller, either as a closure or [ClassName, 'method']
 */
$routes = array(
    [
        "method" => "GET",
        "path" => "/(index.*)?",
        "handler" => [Views\Home::class, 'render'],
    ],
);

return $routes;