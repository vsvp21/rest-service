<?php

namespace Core\Database;

abstract class Driver
{
    protected $host;
    protected $port;
    protected $db;
    protected $username;
    protected $password;

    public function __construct($host, $port, $db, $username, $password)
    {
        $this->host = $host;
        $this->port = $port;
        $this->db = $db;
        $this->username = $username;
        $this->password = $password;
    }

    abstract public function makeConnection();
}