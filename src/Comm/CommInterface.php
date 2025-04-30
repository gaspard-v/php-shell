<?php

namespace GaspardV\PhpShell\Comm;

interface CommInterface
{
    public function get(string $jobId);
    public function send(
        string $workerId,
        string $jobId,
        string $messageType,
        int $size,
        $message
    ): ?int;
}
