<?php

namespace GaspardV\PhpShell\Observable;

abstract class AbstractObservable
{
    protected $listerners = [];
    protected $item = null;

    public function subsriber($callback)
    {
        $this->listerners[] = $callback;
    }
}
