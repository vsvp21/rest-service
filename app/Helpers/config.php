<?php

function config($name)
{
    $config = require __DIR__ . '/../config/app.php';
    if(isset($config[$name])) {
        return $config[$name];
    }
}