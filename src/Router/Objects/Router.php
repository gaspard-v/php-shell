<?php

namespace GaspardV\PhpShell\Router\Objects;

class Router
{
    public readonly array $routes;
    public function __construct(array $routes = [])
    {
        $this->routes = $routes;
    }
}
