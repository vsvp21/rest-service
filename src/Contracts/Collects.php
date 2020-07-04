<?php

namespace Core\Contracts;

interface Collects {
    function get($key);
    function put($key, $value);
    function push($value);
    function forget($key);
    function has($key);
}