<?php

namespace InfoTech\Core;

class Router
{
    protected $routes = [];

    public function add($method, $uri, $controller)
    {
        $this->routes[] = [
            'method' => $method,
            'uri' => $uri,
            'controller' => $controller
        ];
    }

    public function dispatch()
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri = str_ireplace(URL_BASE, '', $uri);
        $method= $_SERVER['REQUEST_METHOD'];

        foreach($this->routes as $routes){
            if ($routes["method"] === $method && $routes["uri"] ===$uri) {
                list($controllerName,$methodName) = explode("@",$routes['controller']);
                $controllerName = "InfoTech\\Controller\\". $controllerName;

                if (class_exists($controllerName)) {
                    $controller = new $controllerName();
                    if (method_exists($controller,$methodName)) {
                        $controller->$methodName();
                        return;
                    }
                }
            }
        }

        http_response_code(404);
        echo "404";
    }
}
