<?php

namespace GaspardV\PhpShell\Comm;

interface CommInterface
{
    public function get(string $jobId);
    public function prepare(
        string $workerId,
        string $jobId,
        string $messageType,
        int $size
    ): SendInterface;

    public function send(
        string $workerId,
        string $jobId,
        string $messageType,
        int $size,
        mixed $message,
    ): int;
}
