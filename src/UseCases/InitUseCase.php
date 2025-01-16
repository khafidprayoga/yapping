<?php

namespace Khafidprayoga\PhpMicrosite\UseCases;

use Doctrine\DBAL\Connection;
use Khafidprayoga\PhpMicrosite\Commons\Dependency;
use Doctrine\ORM\EntityManager;

abstract class InitUseCase extends Dependency
{
    protected Connection $db;
    protected EntityManager $entityManager;

    public function __construct(Connection $db, EntityManager $entityManager)
    {
        parent::__construct();

        $this->db = $db;
        $this->entityManager = $entityManager;
    }

}
