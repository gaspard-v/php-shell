<?php

namespace GaspardV\PhpShell\Comm\SQLite;

use GaspardV\PhpShell\Comm\SendInterface;

class SendMessage implements SendInterface
{
    protected $resource;

    /**
     * 
     * SendMessage constructor
     * @param resource $resource
     */
    public function __construct($resource)
    {
        $this->resource = $resource;
    }

    public function send(callable $callback): mixed
    {
        try {
            return $callback($this->resource);
        } finally {
            fclose($this->resource);
        }
    }
}
