<?php

namespace Khafidprayoga\PhpMicrosite\Providers;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;

class Database implements ProviderInterface
{
    private static ?Connection $db;
    private static EntityManager $entityManager;


    public static function getInstance(): Connection
    {
        if (!isset(self::$db)) {
            $bootstrap = require(APP_ROOT . '/bin/bootstrap.php');
            self::$db = $bootstrap['connection'];
        }
        return self::$db;
    }

    public static function getEntityManager(): EntityManager
    {
        if (!isset(self::$entityManager)) {
            $bootstrap = require(APP_ROOT . '/bin/bootstrap.php');
            self::$entityManager = $bootstrap['entityManager'];
        }
        return self::$entityManager;
    }
}
