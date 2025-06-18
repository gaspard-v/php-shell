<?php

namespace GaspardV\PhpShell\Router;

use GaspardV\PhpShell\Http\MethodEnum;

class RouteBuilder
{
    public readonly string $route;
    public readonly array $methods;
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

    public function setMethods(array $methods): self
    {
        $this->methods = $methods;
        return $this;
    }

    public function setCallback(callable $callback): self
    {
        $clojure = fn(...$o) => $callback(...$o);
        $this->callback = $clojure;
        return $this;
    }

    public function build(): Route
    {
        $allMethods = [
            MethodEnum::GET,
            MethodEnum::POST,
            MethodEnum::PUT,
            MethodEnum::DELETE,
            MethodEnum::PATCH,
            MethodEnum::HEAD,
        ];

        assert($this->route);
    }
}
