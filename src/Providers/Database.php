<?php

namespace Khafidprayoga\PhpMicrosite\Providers;

use Doctrine\DBAL\Connection;

class Database
{
    protected Connection $db;

    public function __construct()
    {
        $bootstrap = require(APP_ROOT . '/bin/bootstrap.php');
        $this->db = $bootstrap['connection'];
    }

    public function getDb(): Connection
    {
        return $this->db;
    }
}
