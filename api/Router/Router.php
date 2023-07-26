<?php

namespace Api\Router;

use Exception;

class Router
{
    private array $routes;

    private function register(string $requestMethod, string $route, callable|array $action) : self
    {
        $this->routes[$requestMethod][$route] = $action;
        return $this;
    }

    public function get(string $route, callable|array $action) : self
    {
        return $this->register("get", $route, $action);
    }

    public function post(string $route, callable|array $action) : self
    {
        return $this->register("post", $route, $action);
    }

    /**
     * @throws Exception
     */
    public function resolve(string $requestUri, string $requestMethod) : mixed
    {
        $route = explode('?', $requestUri)[0];
        $queryString = "";

        if(is_array(explode('?', $requestUri)) && explode('?', $requestUri)[0] != '/backend/'){
            $queryString = explode('?', $requestUri)[1];
        }

        $action = $this->routes[$requestMethod][$route] ?? null;

        if(!$action)
        {
            throw new Exception("Route does not exists");
        }

        if(is_callable($action))
        {
            return call_user_func($action);
        }

        if(is_array($action)){
            [$class, $method] = $action;

            if(class_exists($class)){
                $class = new $class();

                if(method_exists($class, $method)){
                    return call_user_func_array([$class, $method], [$queryString, $_POST]);
                }
            }
        }

        throw new Exception("Action cannot be called");
    }
}