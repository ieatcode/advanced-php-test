<?php

namespace App\System;

use Exception;

class Router
{
    /**
     * @var array[]
     */
    public array $routes = [
        'GET' => []
    ];

    /**
     * @param $file
     * @return static
     */
    public static function load($file)
    {
        $router = new static;

        require $file;

        return $router;
    }

    /**
     * @param $uri
     * @param $controller
     */
    public function get($uri, $controller)
    {
        $this->routes['GET'][$uri] = $controller;
    }

    /**
     * @param $uri
     * @param $requestType
     * @return mixed
     * @throws Exception
     */
    public function direct($uri, $requestType)
    {
        if (array_key_exists($uri, $this->routes[$requestType])) {
            return $this->callAction(
                ...explode(
                    '@',
                    $this->routes[$requestType][$uri]
                )
            );
        }

        throw new Exception("Page not found");
    }

    /**
     * @param $controller
     * @param $action
     * @return mixed
     * @throws Exception
     */
    public function callAction($controller, $action)
    {
        $controller = "App\\Controllers\\{$controller}";
        $controller = new $controller;

        if (!method_exists($controller, $action)) {
            throw new Exception(
                "{$controller} does not respond to the {$action} action"
            );
        }

        return $controller->$action();
    }

}