<?php

namespace GaspardV\PhpShell\Router;

class Router
{
    private array $routes = [];
    public function __construct(array $routes = [])
    {
        $this->routes = $routes;
    }
}
