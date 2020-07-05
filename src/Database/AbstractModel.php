<?php

namespace Core\Database;

use Core\Support\Collection;
use Core\Database\MySQLDriver;
use Core\Database\DBConnection;

abstract class AbstractModel {
    protected $tableName;
    protected $connection;
    protected $query;

    public function __construct() {
        $this->connection = (
            new DBConnection(config('database'))
        )->getConnection();

        return $this;
    }

    public function save($update = false) {
        $class = new \ReflectionClass($this);
        $tableName = $this->getTableName();

        $propsToImplode = [];
        foreach ($this->fillables() as $property) {
            $propertyName = $property->getName();
            $propsToImplode[] = '`'.$propertyName.'` = "'.$this->{$propertyName}.'"';
        }

        $setClause = implode(',',$propsToImplode);
        $sqlQuery = '';
        
        if($update == false) {
            $sqlQuery = "INSERT INTO `$tableName` SET $setClause, `created_at` = now(), `updated_at` = NULL";
        } else {
            $sqlQuery = "UPDATE `$tableName` SET $setClause, `updated_at` = now() WHERE id = " . $this->id;
        }

        $result = $this->connection->exec($sqlQuery);

        if ($this->connection->errorCode() != "0000") {
            throw new \Exception($this->connection->errorInfo()[2]);
        }

        return $result;
    }

    public function morph(array $object)
    {
        $class = new \ReflectionClass($this);
        
        $entity = $class->newInstance();
        foreach($class->getProperties(\ReflectionProperty::IS_PUBLIC) as $prop) {
            if (isset($object[$prop->getName()])) {
              $prop->setValue($entity, $object[$prop->getName()]);
            }
        }
        
        return $entity;
    }

    private function fillables() {
        $class = new \ReflectionClass($this);
        
        return array_filter($class->getProperties(\ReflectionProperty::IS_PUBLIC), function($property) {
            return in_array($property->getName(), $this->fillable);
        });
    }

    public function select($columns = []) {
        if($columns == false) {
            $this->query = 'SELECT * FROM ' . $this->getTableName();
        } else {
            $this->query = 'SELECT ' . implode(',', $columns) . ' FROM ' . $this->getTableName();
        }

        return $this;
    }

    public function orderBy($column, $dir)
    {
        $this->query .=  " ORDER BY $column $dir ";

        return $this;
    }

    public function limit($n)
    {
        $this->query .= " LIMIT $n ";

        return $this;
    }

    public function find($options = []) {
        if(strlen($this->query) == 0) {
            $this->query = 'SELECT * FROM ' . $this->getTableName();
        }
    
        if (is_array($options)) {
            $whereClause = '';
            $whereConditions = [];

            if (!empty($options)) {
                foreach ($options as $key => $value) {
                    $whereConditions[] = '`' . $key . '` = "' . $value . '"';
                }
                $whereClause = " WHERE " . implode(' AND ', $whereConditions);
                $this->query .= $whereClause;
            }
        } elseif (is_string($options)) { 
            $this->query .= ' WHERE ' . $options;
        } else {
            throw new \Exception('Wrong parameter type of options');
        }
        
        return $this->fetch();
    }

    public function getTableName() {
        if ($this->tableName != '') {
            return $this->tableName;
        }
        return strtolower($class->getShortName());
    }

    public function __toString() {
        return json_encode($this);
    }

    public function fetch() {
        $result = new Collection();
        $raw = $this->connection->query($this->query);
        
        foreach ($raw as $rawRow) {;
            $result->push($this->morph($rawRow));
        }
        return $result;
    }
}