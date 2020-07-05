<?php

namespace Core\Database;

use Core\Contracts\Database\Connection;
use Core\Factory\SimpleConnectionFactory;

class DBConnection implements Connection
{
    protected $connection;
    protected $driver;
    
    public function __construct($config = []) {
        switch($config['driver']) {
            case 'mysql':
                $this->driver = SimpleConnectionFactory::createMysqlConnection($config);
            break;
            case 'postresql':
                $this->driver = SimpleConnectionFactory::createPostgreConnection($config);
            break;
            default:
                $this->driver = SimpleConnectionFactory::createMysqlConnection($config);
        }
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