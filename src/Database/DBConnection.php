<?php

namespace Core\Database;

use Core\Database\Driver;
use Core\Contracts\Database\Connection;

class DBConnection implements Connection
{
    protected $connection;
    protected $driver;

    public function __construct(Driver $driver) {
        $this->driver = $driver;
    }

    public function getConnection() {
        if($this->connection) {
            return $this->connection;
        }

        return $this->connect();
    }

    public function connect() {
        return $this->driver->makeConnection();
    }

    public function close() {
        return $this->driver = null;
    }
}