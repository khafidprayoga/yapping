<?php

namespace Khafidprayoga\PhpMicrosite\Services;

use Psr\Container\ContainerInterface;

class ServiceMediator implements ServiceMediatorInterface
{
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function get(string $serviceName)
    {
        return $this->container->get($serviceName);

    }
}
