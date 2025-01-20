<?php

namespace Khafidprayoga\PhpMicrosite\Controllers;

use DI\ContainerBuilder;
use Khafidprayoga\PhpMicrosite\Commons\Dependency;
use Khafidprayoga\PhpMicrosite\Providers\Database;
use Khafidprayoga\PhpMicrosite\Providers\Serializer;
use Khafidprayoga\PhpMicrosite\Providers\TwigEngine;
use Khafidprayoga\PhpMicrosite\Services\ServiceMediatorInterface;
use Khafidprayoga\PhpMicrosite\Services\UserServiceInterface;
use Khafidprayoga\PhpMicrosite\Services\PostServiceInterface;
use Khafidprayoga\PhpMicrosite\Services\ServiceMediator;
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
    protected ServerRequestInterface $request;
    protected UserServiceInterface $userService;

    protected PostServiceInterface $postService;
    protected EntityManager $entityManager;
    protected SymfonySerializer $serializer;

    public function __construct()
    {
        parent::__construct();


        // add strict type for http request-response lifecycle on the router
        $psr17Factory = new Psr17Factory();
        $creator = new ServerRequestCreator(
            serverRequestFactory: $psr17Factory,
            uriFactory: $psr17Factory,
            uploadedFileFactory: $psr17Factory,
            streamFactory: $psr17Factory,
        );

        $this->request = $creator->fromGlobals();


        $containerBuild = new ContainerBuilder();
        $containerBuild->useAutowiring(false);
        $containerBuild->useAttributes(true);

        // register dependency container
        $containerBuild->addDefinitions([
            // Singletons providers
            TwigEngine::class => value(TwigEngine::getInstance()),
            Connection::class => value(Database::getInstance()),
            EntityManager::class => value(Database::getEntityManager()),
            Serializer::class => value(Serializer::getInstance()),

            // Services
            UserServiceInterface::class => autowire(UserServiceInterfaceImpl::class),
            PostServiceInterface::class => autowire(PostServiceInterfaceImpl::class),

            // Mediator Pool
            ServiceMediatorInterface::class => autowire(ServiceMediator::class),
        ]);

        // building container
        $container = $containerBuild->build();


        $mediator = $container->get(ServiceMediatorInterface::class);

        $this->twig = $container->get(TwigEngine::class);
        $this->entityManager = $container->get(EntityManager::class);
        $this->serializer = $container->get(Serializer::class);
        $this->userService = $mediator->get(UserServiceInterface::class);
        $this->postService = $mediator->get(PostServiceInterface::class);

    }

    public function render(string $template, array $data = [], int $statusCode = 200): void
    {
        http_response_code($statusCode);
        $templateName = $template . ".twig";
        $view = $this->twig->render($templateName, $data);
        echo $view;

    }

    protected function getJsonBody(): array
    {
        $body = (string) $this->request->getBody();
        return json_decode($body, true);
    }
}
