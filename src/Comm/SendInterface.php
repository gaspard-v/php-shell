<?php

namespace GaspardV\PhpShell\Comm;

interface SendInterface
{
    public function send(callable $callback): mixed;
}
