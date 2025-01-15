<?php
declare(strict_types=1);
define('APP_ROOT', __DIR__);

ini_set('display_errors', 1);
require __DIR__ . '/vendor/autoload.php';

use Khafidprayoga\PhpMicrosite\Routes\Route;

$route = new Route();
$route->dispatch();
