<?php

namespace GaspardV\PhpShell\Observable;

use Set;

abstract class AbstractObservable
{
    protected Set $listeners;
    protected mixed $item;

    public function __construct()
    {
        $this->listeners = new Set();
    }

    public function subscribe($callback): callable
    {
        $this->listeners->add($callback);
        return fn() => $this->listeners->delete($callback);
    }
    public function getCurrentItem(): mixed
    {
        return $this->item;
    }

    public function notify(): void
    {
        foreach ($this->listeners as $listener) {
            $listener($this->item);
        }
    }
}
