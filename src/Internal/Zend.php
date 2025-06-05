<?php

namespace GaspardV\PhpShell\Internal;

class Zend
{
    public static function infiniteExecTime(): void
    {
        set_time_limit(0);
    }
}
