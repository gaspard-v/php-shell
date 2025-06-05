<?php

class Set implements Countable, IteratorAggregate
{
    private array $values = [];

    public function __construct(?iterable $iterable = null)
    {
        if ($iterable === null) return;
        foreach ($iterable as $value) {
            $this->add($value);
        }
    }

    public function add($value): self
    {
        $key = self::getKey($value);
        $this->values[$key] = $value;
        return $this;
    }

    public function clear(): void
    {
        $this->values = [];
        return;
    }

    public function delete($value): bool
    {
        $key = self::getKey($value);
        $present = isset($this->values[$key]);

        if (!$present) return false;
        unset($this->values[$key]);
        return true;
    }

    public function forEach(callable $callback): void
    {
        foreach ($this->values as $key => $value) {
            $callback($value, $key, $this);
        }
    }

    public function entries(): Generator
    {
        foreach ($this->values as $key => $value) {
            yield $key => $value;
        }
    }

    public function has($value): bool
    {
        return isset($this->values[$value]);
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator(array_values($this->values));
    }

    public function size(): int
    {
        return count($this->values);
    }

    public function count(): int
    {
        return $this->size();
    }

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

        if (is_array($value)) {
            $key = serialize($value);
        }

        if (null === $key) {
            $key = (string)$value;
        }

        return $type . '_' . md5($key);
    }
}
