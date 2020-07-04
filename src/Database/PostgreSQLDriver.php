<?php

namespace Core\Database;

use PDO;

class PostgreSQLDriver extends Driver
{
    public function makeConnection()
    {
        try {
            $connection = new PDO(
                "pgsql:host=" . $this->host . ";port=" . $this->port . "dbname=" . $this->db,
                $this->username,
                $this->password
            );

            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $connection;
        } catch(PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }
}