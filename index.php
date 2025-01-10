<?php
declare(strict_types=1);

use Bramus\Router\Router;
use Khafidprayoga\PhpMicrosite\Views;
use Khafidprayoga\PhpMicrosite\Controllers;

require __DIR__ . '/vendor/autoload.php';

$router = new Router();

$router->get("/", function () {
    echo Views\Home::render();
});

$router->run();

