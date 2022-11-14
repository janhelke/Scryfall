<?php

namespace Janhelke\Scryfall;

use Iterator;

class ScryfallIterator implements Iterator
{
    protected array $values = [];

    public function __construct($values)
    {
        $this->values = $values;
    }

    public function count(): int
    {
        return count($this->values);
    }

    public function rewind(): void
    {
        reset($this->values);
    }

    public function current(): mixed
    {
        return current($this->values);
    }

    public function key(): string|int|null
    {
        return key($this->values);
    }

    public function next(): mixed
    {
        return next($this->values);
    }

    public function valid(): bool
    {
        $key = key($this->values);
        return $key !== null && $key !== false;
    }
}
