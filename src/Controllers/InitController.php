<?php

namespace Khafidprayoga\PhpMicrosite\Controllers;

use DI\ContainerBuilder;
use Khafidprayoga\PhpMicrosite\Commons\Dependency;
use Khafidprayoga\PhpMicrosite\Commons\HttpException;
use Khafidprayoga\PhpMicrosite\Models\DTO\JwtClaimsDTO;
use Khafidprayoga\PhpMicrosite\Providers\Database;
use Khafidprayoga\PhpMicrosite\Providers\Serializer;
use Khafidprayoga\PhpMicrosite\Providers\TwigEngine;
use Khafidprayoga\PhpMicrosite\Services\AuthServiceInterface;
use Khafidprayoga\PhpMicrosite\Services\ServiceMediatorInterface;
use Khafidprayoga\PhpMicrosite\Services\UserServiceInterface;
use Khafidprayoga\PhpMicrosite\Services\PostServiceInterface;
use Khafidprayoga\PhpMicrosite\Services\ServiceMediator;
use Khafidprayoga\PhpMicrosite\UseCases\AuthenticationServiceInterfaceImpl;
use Khafidprayoga\PhpMicrosite\UseCases\PostServiceInterfaceImpl;
use Khafidprayoga\PhpMicrosite\UseCases\UserServiceInterfaceImpl;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7Server\ServerRequestCreator;
use Psr\Http\Message\ServerRequestInterface;
use Twig\Environment;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Serializer\Serializer as SymfonySerializer;

use function DI\autowire;
use function DI\value;

class InitController extends Dependency
{
    private Environment $twig;
    protected UserServiceInterface $userService;

    protected PostServiceInterface $postService;
    protected AuthServiceInterface $authService;
    protected EntityManager $entityManager;
    protected SymfonySerializer $serializer;

    public function __construct()
    {
        parent::__construct();
        $containerBuild = new ContainerBuilder();

        // register dependency container
        $containerBuild->addDefinitions([
            // Singletons providers
            TwigEngine::class => value(TwigEngine::getInstance()),
            Connection::class => value(Database::getInstance()),
            EntityManager::class => value(Database::getEntityManager()),
            SymfonySerializer::class => value(Serializer::getInstance()),

            // Services
            UserServiceInterface::class => autowire(UserServiceInterfaceImpl::class),
            PostServiceInterface::class => autowire(PostServiceInterfaceImpl::class),
            AuthServiceInterface::class => autowire(AuthenticationServiceInterfaceImpl::class),

            // Mediator Pool
            ServiceMediatorInterface::class => autowire(ServiceMediator::class),
        ]);

        // building container
        $container = $containerBuild->build();


        $mediator = $container->get(ServiceMediatorInterface::class);

        $this->twig = $container->get(TwigEngine::class);
        $this->entityManager = $container->get(EntityManager::class);
        $this->serializer = $container->get(SymfonySerializer::class);
        $this->userService = $mediator->get(UserServiceInterface::class);
        $this->postService = $mediator->get(PostServiceInterface::class);
        $this->authService = $mediator->get(AuthServiceInterface::class);

    }

    public function render(string $template, array $data = [], int $statusCode = 200): void
    {
        http_response_code($statusCode);
        $templateName = $template . ".twig";
        $view = $this->twig->render($templateName, $data);
        echo $view;

        exit;
    }

    protected function getJsonBody(ServerRequestInterface $req): array
    {
        $body = (string)$req->getBody();
        value($body);
        return json_decode($body, true);
    }

    protected function getFormData(ServerRequestInterface $req): array
    {
        return $req->getParsedBody();
    }

    protected function responseJson(?HttpException $err, mixed $data = [], ?int $statusCode = 200): void
    {

        header('Content-Type: application/json');

        $response = [];
        if (isset($err)) {
            $response['statusCode'] = $err->getCode();
            $response['status'] = 'ERROR';
            $response['errorMessage'] = $err->getMessage();
            $statusCode = $err->getCode();
        } else {
            $response['statusCode'] = $statusCode;
            $response['status'] = 'SUCCESS';
            $response['data'] = $data;
        }

        http_response_code($statusCode);
        echo $this->serializer->serialize($response, 'json');
        exit;
    }

    protected function getClaims(ServerRequestInterface $req): JwtClaimsDTO
    {
        $claims = $req->getAttribute("claims");
        return new JwtClaimsDTO($claims);
    }
}
