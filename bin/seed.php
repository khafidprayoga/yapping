<?php

require_once "vendor/autoload.php";
const APP_ROOT = __DIR__ . '/..';

use DI\ContainerBuilder;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;
use Faker\Factory;
use Khafidprayoga\PhpMicrosite\Configs\AppConfig;
use Khafidprayoga\PhpMicrosite\Models\DTO\PostingRequestDTO;
use Khafidprayoga\PhpMicrosite\Models\DTO\UserDTO;
use Khafidprayoga\PhpMicrosite\Providers\Database;
use Khafidprayoga\PhpMicrosite\Providers\Serializer;
use Khafidprayoga\PhpMicrosite\Services\AuthServiceInterface;
use Khafidprayoga\PhpMicrosite\Services\PostServiceInterface;
use Khafidprayoga\PhpMicrosite\Services\ServiceMediator;
use Khafidprayoga\PhpMicrosite\Services\ServiceMediatorInterface;
use Khafidprayoga\PhpMicrosite\Services\UserServiceInterface;
use Khafidprayoga\PhpMicrosite\UseCases\AuthenticationServiceInterfaceImpl;
use Khafidprayoga\PhpMicrosite\UseCases\PostServiceInterfaceImpl;
use Khafidprayoga\PhpMicrosite\UseCases\UserServiceInterfaceImpl;
use function DI\autowire;
use function DI\value;


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

$containerBuild = new ContainerBuilder();

// register dependency container
$containerBuild->addDefinitions([
    // Singletons providers
    Connection::class => value(Database::getInstance()),
    EntityManager::class => value(Database::getEntityManager()),

    // Services
    UserServiceInterface::class => autowire(UserServiceInterfaceImpl::class),
    PostServiceInterface::class => autowire(PostServiceInterfaceImpl::class),
    AuthServiceInterface::class => autowire(AuthenticationServiceInterfaceImpl::class),

    // Mediator Pool
    ServiceMediatorInterface::class => autowire(ServiceMediator::class),
]);

// building container
try {
    $container = $containerBuild->build();
} catch (Exception $e) {
    exit(1);
}

$mediator = $container->get(ServiceMediatorInterface::class);
$userUC = $container->get(UserServiceInterface::class);
$postUc = $container->get(PostServiceInterface::class);
$faker = Factory::create();

$DEFAULT_PASS = 'Supersecret123@!#';
for ($i = 0; $i < 10; $i++) {
    $request = new UserDTO([
        'fullName' => $faker->name(),
        'username' => $faker->email(),
        'password' => $DEFAULT_PASS,
        'passwordVerify' => $DEFAULT_PASS,
    ]);

    $userCreated = $userUC->createUser($request);
    $user = $userCreated[0];

    // creating post
    for ($j = 0; $j < 10; $j++) {
        $req = new PostingRequestDTO(
            [
                'title' => $faker->sentence,
                'content' => $faker->text(255),
                'userId' => $user['id'],
            ]
        );

        $postUc->createNewPost($req);
    }
}