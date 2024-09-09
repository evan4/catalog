<?php

namespace Catalog;

class Router
{
    private $routes = [];
    private $method;
    private $uri;

    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->uri = $_SERVER['REQUEST_URI'];
    }

    public function add($url, $path, $method = 'get')
    {
        $action = explode('@', $path);
        $method = strtoupper($method);
        if (!array_key_exists($method, $this->routes)) {
            $this->routes[$method] = [];
        }
        array_push($this->routes[$method], [$url => $action]);
    }

    public function notFound()
    {
        http_response_code(404);
        $page = new View('errors@404');
        $data = [];
        $data['url'] = $this->uri;
        $page->render($data);
    }

    public function dispatch()
    {
        foreach ($this->routes[$this->method] as $value) {
            $url = array_keys($value);
            $data = $value[$url[0]];

            if( $url[0] === $this->uri ){

                $controller = 'Catalog\Controllers\\'.$data[0];
                $instance = new $controller();
                $method = $data[1];
                 
                return $instance->$method();
            }
        }

        $this->notFound();
    }

}
