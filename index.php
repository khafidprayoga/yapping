<?php

declare(strict_types=1);
const APP_ROOT = __DIR__;


error_reporting(E_ALL);
require __DIR__ . '/vendor/autoload.php';

use Khafidprayoga\PhpMicrosite\Routes\Route;
use Khafidprayoga\PhpMicrosite\Providers\Serializer;
use Khafidprayoga\PhpMicrosite\Configs\AppConfig;
use Khafidprayoga\PhpMicrosite\Commons\AppMode;

$configFile = 'applications.json';
if (!file_exists($configFile)) {
    error_log('FATAL: Application configuration file does not exist.');
    exit(1);
}

$configMetadata = @file_get_contents('applications.json');
if (!$configMetadata) {
    error_log('FATAL: Application configuration file is empty.');
    exit(1);
}

// prepare json parser
$serializer = Serializer::getInstance();

// read config files to typed AppConfig
$appConf = $serializer
    ->deserialize(
        $configMetadata,
        AppConfig::class,
        'json',
    );

define('APP_CONFIG', $appConf);

// if prod we use webserver .htaccess instead
if ($appConf->appMode === AppMode::DEVELOPMENT) {
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
}


// silent error on the browser, only show in cli
if ($appConf->appMode === AppMode::PRODUCTION) {
    ini_set('display_errors', "Off");
    ini_set("date.timezone", "Asia/Jakarta");
}

$route = new Route();
$route->dispatch();
