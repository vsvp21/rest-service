<?php

namespace Core\Support;

use ArrayIterator;
use IteratorAggregate;
use Core\Contracts\Collects;

class Collection implements Collects, IteratorAggregate
{
    private $items = [];
    
    public function __construct(array $items = [])
    {
        if($items != false) {
            $this->items = $items;
        }
    }

    public function get($key)
    {
        if($this->has($key)) {
            return $this->items[$key];
        }

        return null;
    }

    public function put($key, $value)
    {
        $this->items[$key] = $value;
    }

    public function push($value)
    {
        $this->items[] = $value;
    }

    public function forget($key)
    {
        unset($this->items[$key]);
    }

    public function has($key)
    {
        return array_key_exists($key, $this->items);
    }

    public function __toString()
    {
        return json_encode($this->items);
    }

    public function getIterator() {
        return new ArrayIterator($this->items);
    }
}