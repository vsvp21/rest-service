<?php

namespace Core\Factory;

use Core\Database\MySQLDriver;
use Core\Database\PostgreSQLDriver;

class SimpleConnectionFactory
{
    static public function createMysqlConnection($config)
    {
        return new MySQLDriver(
            $config['host'],
            $config['port'],
            $config['database'],
            $config['username'],
            $config['password']
        );
    }

    static public function createPostgreConnection($config)
    {
        return new PostgreSQLDriver(
            $config['host'],
            $config['port'],
            $config['database'],
            $config['username'],
            $config['password']
        );
    }
}