<?php

class Set implements ArrayAccess, Countable, IteratorAggregate
{
    private array $values = [];
    private array $hashes = [];

    public function __construct(?iterable $iterable = null)
    {
        if ($iterable === null) return;
        foreach ($iterable as $value) {
        }
    }

    public function add($value) {}
    private static function getKey($value): string
    {
        $key = null;
        $type = gettype($value);

        if (is_object($value)) {
            $key = spl_object_id($value);
        }
        if (is_resource($value)) {
            $key = get_resource_id($value);
        }

        if (null === $key) {
            $key = serialize($value);
        }

        return $type . '_' . md5($key);
    }
}
