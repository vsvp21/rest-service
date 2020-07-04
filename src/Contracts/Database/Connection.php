<?php

namespace Core\Contracts\Database;

interface Connection {
    function getConnection();
    function connect();
    function close();
}