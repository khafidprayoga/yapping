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
        handler: [Controllers\Home::class, 'index'],
    )
);

return $routes;
