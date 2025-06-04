<?php

namespace GaspardV\PhpShell\Http;

class Comm
{
    public function __construct()
    {
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');
    }
}
