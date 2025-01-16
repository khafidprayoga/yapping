<?php
declare(strict_types=1);
const APP_ROOT = __DIR__;

//ini_set('log_errors', "1");
//ini_set('display_errors', "1");
//ini_set("date.timezone", "Asia/Jakarta");

error_reporting(E_ALL);
require __DIR__ . '/vendor/autoload.php';

use Khafidprayoga\PhpMicrosite\Routes\Route;

$route = new Route();
$route->dispatch();
