<?php

namespace Api\Database;

use PDO;
use PDOException;

/**
 * @mixin PDO
 */
class DB
{
    private PDO $pdo;
    public function __construct(array $dbConfig)
    {
        try{
            $this->pdo = new PDO($dbConfig['driver'] . ':host=' .
                $dbConfig['host'] . ';dbname=' .
                $dbConfig['database'],
                $dbConfig['user'],
                $dbConfig['password']);
        }catch (PDOException $e){
            echo $e->getMessage();
        }
    }

    public function __call(string $name, array $arguments)
    {
        return call_user_func_array([$this->pdo, $name], $arguments);
    }
}