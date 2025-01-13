<?php
declare(strict_types=1);

ini_set('display_errors', 1);
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/bootstrap.php';

use Khafidprayoga\PhpMicrosite\Routes\Route;

$route = new Route();
$route->dispatch();
