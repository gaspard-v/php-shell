<?php

namespace GaspardV\PhpShell\Router\Builders;

use GaspardV\PhpShell\Http\MethodEnum;
use GaspardV\PhpShell\Interfaces\Builder;
use GaspardV\PhpShell\Router\Objects;

class Route implements Builder
{
    public readonly string $route;
    public readonly ?array $methods;
    public readonly \Closure $callback;


    public function setRoute(string $route): self
    {
        assert(!!$route);
        $this->route = $route;
        return $this;
    }

    public function addMethod(MethodEnum $method): self
    {
        $this->methods[] = $method;
        return $this;
    }

    /**
     * Summary of setMethods
     * @param MethodEnum[] $methods
     * @return Route
     */
    public function setMethods(array $methods): self
    {
        $this->methods = $methods;
        return $this;
    }

    public function setCallback(callable $callback): self
    {
        assert(is_callable($callback));
        $clojure = fn(...$o) => $callback(...$o);
        $this->callback = $clojure;
        return $this;
    }

    public function build(): Objects\Route
    {
        assert($this->route);
        assert(is_callable($this->callback));
        $methods = $this->methods ?? MethodEnum::cases();

        return new Objects\Route(
            route: $this->route,
            methods: $methods,
            callback: $this->callback
        );
    }
}
