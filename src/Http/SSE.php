<?php

namespace GaspardV\PhpShell\Http;

use GaspardV\PhpShell\Internal\Zend;

class SSE
{
    public function __construct()
    {
        Zend::infiniteExecTime();
    }

    protected static function sendConnHeaders(): void
    {
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');
        header("X-Accel-Buffering: no");
    }
}
