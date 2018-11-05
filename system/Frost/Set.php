<?php

namespace Frost;

use
    ArrayAccess,
    ArrayIterator,
    Countable,
    IteratorAggregate,
    Serializable;

use function
    array_divide,
    array_map,
    array_push,
    array_walk,
    count,
    is_null,
    serialize,
    unserialize,

    array_dot,
    array_except,
    array_first,
    array_flatten,
    array_forget,
    array_get,
    array_has,
    array_last,
    array_only,
    array_prepend,
    array_random,
    array_set,
    arary_sort,
    array_where,
    first,
    last;

class Set implements IteratorAggregate, ArrayAccess, Countable, Serializable
{
    private $array = [];

    public function __construct(array $array)
    {
        $this->array = $array;
    }

    public function add($value, $key = null): self
    {
        return $this->pushSet(\array_add($this->array, $value, $key));
    }

    public function count(): int
    {
        return count($this->array);
    }

    public function divide(): array
    {
        return array_divide($this->array);
    }

    public function dot(): self
    {
        return $this->pushSet(array_dot($this->array));
    }

    public function except(array $keys): self
    {
        return $this->pushSet(array_except($this->array, $keys));
    }

    public function filter(callable $callback): self
    {
        return $this->pushSet(array_where($this->array, $callback));
    }

    public function first(callable $callback = null, $default = null)
    {
        return $callback ?
            array_first($this->array, $callback, $default) :
            first($this->array, $default);
    }

    public function flatten(): self
    {
        return $this->pushSet(array_flatten($this->array));
    }

    public function forEach(callable $callback): self
    {
        foreach ($this->array AS $key => $val) {
            $callback($val, $key);
        }
        return $this;
    }

    public function forget(string $key): self
    {
        $array = $this->array;
        array_forget($array, $key);
        $this->pushSet($array);
        return $this;
    }

    public function get($key = null, $default = null)
    {
        if ( ! $key) {
            return $this->array;
        }

        return array_get($this->array, $key, $default);
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->array);
    }

    public function has($key): bool
    {
        return array_has($this->array, $key);
    }

    public function last(callable $callback = null, $default = null)
    {
        return $callback ?
            array_last($this->array, $callback, $default) :
            last($this->array, $default);
    }

    public function length(): int
    {
        return $this->count();
    }

    public function map(callable $callback): self
    {
        return $this->pushSet(array_map($callback, $this->array));
    }

    public function offsetSet($key, $value)
    {
        if (is_null($key)) {
            $this->array[] = $value;
        } else {
            $this->array[$key] = $value;
        }
    }

    public function offsetExists($key)
    {
        return isset($this->array[$key]);
    }

    public function offsetUnset($key)
    {
        unset($this->array[$key]);
    }

    public function offsetGet($key)
    {
        return $this->offsetExists($key) ? $this->array[$key] : null;
    }

    public function only(array $keys): self
    {
        return $this->pushSet(array_only($this->array, $keys));
    }

    public function prepend($value): self
    {
        $array = $this->array;
        array_prepend($array, $value);
        return $this->pushSet($array);
    }

    public function push($value): self
    {
        $array = $this->array;
        array_push($array, $value);
        return $this->pushSet($array);
    }

    public function pushSet(array $array): self
    {
        $this->array = $array;
        return $this;
    }

    public function random(int $elements = 1): self
    {
        return $this->pushSet(array_random($this->array, $elements));
    }

    public function serialize(): string
    {
        return serialize($this->array);
    }

    public function set($key, $value, bool $overwrite = true): self
    {
        $array = $this->array;
        array_set($array, $key, $value, $overwrite);
        return $this->pushSet($array);
    }

    public function sort(callable $callback = null): self
    {
        return $this->pushSet(rray_sort($this->array, $callback));
    }

    public function unserialize(string $data): self
    {
        return $this->pushSet(unserialize($data));
    }

    public function walk(callable $callback): self
    {
        array_walk($this->array, $callback);
        return $this;
    }

}
