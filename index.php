<?php
declare(strict_types=1);
const APP_ROOT = __DIR__;

//ini_set('log_errors', "1");
//ini_set('display_errors', "1");
//ini_set("date.timezone", "Asia/Jakarta");

error_reporting(E_ALL);
require __DIR__ . '/vendor/autoload.php';

use Khafidprayoga\PhpMicrosite\Routes\Route;

// todo add condition for development and prod
// if prod we use webserver .htaccess instead
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$filePath = __DIR__ . '/public' . $requestUri;

if (str_starts_with($requestUri, '/public/')) {
    $filePath = __DIR__ . $requestUri;

    if (file_exists($filePath) && is_file($filePath)) {
        $mimeType = mime_content_type($filePath);
        header("Content-Type: $mimeType");
        readfile($filePath);
        exit;
    }

    http_response_code(404);
    echo "File not found";
    exit;
}

$route = new Route();
$route->dispatch();
