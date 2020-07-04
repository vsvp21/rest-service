<?php

namespace Core\Database;

use Core\Support\Collection;
use Core\Database\MySQLDriver;
use Core\Database\DBConnection;

abstract class AbstractModel {
    protected $tableName;
    protected $connection;
    private $class;

    public function __construct() {
        if(config('database')['driver'] == 'mysql') {
            $credentials = config('database');
        
            $this->connection = (
                new DBConnection(new MySQLDriver(
                    $credentials['host'],
                    $credentials['port'],
                    $credentials['database'],
                    $credentials['username'],
                    $credentials['password']
                ))
            )->getConnection();
        }

        return $this;
    }

    public function save() {

        $this->class = new \ReflectionClass($this);
        $tableName = $this->getTableName();

        $propsToImplode = [];
        foreach ($this->fillables() as $property) {
            $propertyName = $property->getName();
            $propsToImplode[] = '`'.$propertyName.'` = "'.$this->{$propertyName}.'"';
        }

        $setClause = implode(',',$propsToImplode);
        $sqlQuery = '';

        if ($this->id > 0) {
            $sqlQuery = "UPDATE `$tableName` SET $setClause, `updated_at` = now() WHERE id = " . $this->id;
        } else {
            $sqlQuery = "INSERT INTO `$tableName` SET $setClause, `created_at` = now(), `updated_at` = NULL";
        }

        $result = $this->connection->exec($sqlQuery);

        if ($this->connection->errorCode() != "0000") {
            throw new \Exception($this->connection->errorInfo()[2]);
        }
    
        $this->connection = null;
        return $result;
    }

    public function morph(array $object)
    {
        $this->class = new \ReflectionClass($this);
        foreach($this->class->getProperties(\ReflectionProperty::IS_PUBLIC) as $prop) {
            if (isset($object[$prop->getName()])) {
              $prop->setValue($this, $object[$prop->getName()]);
            }
        }
        
        return $this;
    }

    private function fillables() {
        return array_filter($this->class->getProperties(\ReflectionProperty::IS_PUBLIC), function($property) {
            return in_array($property->getName(), $this->fillable);
        });
    }

    public function find($options = []) {

        $result = new Collection();

        $query = 'SELECT * FROM ' . $this->getTableName();
    
        if (is_array($options)) {
            $whereClause = '';
            $whereConditions = [];

            if (!empty($options)) {
                foreach ($options as $key => $value) {
                    $whereConditions[] = '`' . $key . '` = "' . $value . '"';
                }
                $whereClause = " WHERE " . implode(' AND ', $whereConditions);
                $query .= $whereClause;
            }
        } elseif (is_string($options)) { 
            $query .= ' WHERE ' . $options;
        } else {
            throw new \Exception('Wrong parameter type of options');
        }
        
        $raw = $this->connection->query($query);

        foreach ($raw as $rawRow) {
            $result->push($this->morph($rawRow));
        }
        
        $this->connection = null;
        return $result;
    }

    public function getTableName() {
        if ($this->tableName != '') {
            return $this->tableName;
        }
        return strtolower($this->class->getShortName());
    }

    public function __toString() {
        return json_encode($this);
    }
}