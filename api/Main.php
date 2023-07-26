<?php

namespace Api;

use Api\Database\DB;
use Api\Router\Router;
use Exception;

class Main
{
    private static DB $db;
    public function __construct(protected Router $router, protected array $request, protected Config $dbConfig)
    {
        static::$db = new DB($this->dbConfig->db ?? null);
    }

    public static function getDbInstance(): DB
    {
        return static::$db;
    }
    public function run(): void
    {
        try{
            echo $this->router->resolve(
                $this->request['uri'],
                strtolower($this->request['method'])
            );
        } catch (Exception) {
            http_response_code(404);
        }

    }
}