<?php


namespace GaspardV\PhpShell\Router\Builders;

use GaspardV\PhpShell\Interfaces\Builder;
use GaspardV\PhpShell\Router\Objects;

class Router implements Builder
{
    public readonly array $routes;
    public function __construct()
    {
        $this->routes = [];
    }

    public function addRoute(Objects\Route $route): self
    {
        $this->routes[] = $route;
        return $this;
    }

    /**
     * Override routes member
     * @param Objects\Route[] $routes
     * @return self
     */
    public function setRoutes(array $routes): self
    {
        $this->routes = $routes;
        return $this;
    }

    public function build(): Objects\Router
    {
        return new Objects\Router($this->routes);
    }
}
