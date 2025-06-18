<?php

namespace GaspardV\PhpShell\Router\Objects;

class Route
{
    public readonly string $route;
    public readonly array $methods;
    public readonly \Closure $callback;

    public function __construct(
        string $route,
        array $methods,
        \Closure $callback
    ) {
        $this->route = $route;
        $this->methods = $methods;
        $this->callback = $callback;
    }
}
