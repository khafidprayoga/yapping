<?php
declare(strict_types=1);
ini_set('display_errors', 1);

use Khafidprayoga\PhpMicrosite\Routes\Route;

require __DIR__ . '/vendor/autoload.php';

$route = new Route();
$route->dispatch();
