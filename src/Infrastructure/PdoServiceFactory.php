<?php

namespace Messere\Cart\Infrastructure;

class PdoServiceFactory
{
    public static function createPdo(): \PDO
    {
        $dbFileLocation = getenv('DATABASE_FILE');
        $pdo = new \PDO("sqlite:$dbFileLocation");
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        return $pdo;
    }
}
