<?php

namespace App\Controllers;

use PDO;
use Countable;
use App\Models\Post;
use App\Events\CSVUploaded;
use Core\Support\Collection;
use Core\Database\MySQLDriver;
use Core\Database\DBConnection;

class ProcessController
{
    public function store()
    {
        if(count($_FILES) == false || !isset($_FILES['csvFile'])) {
            header('HTTP/1.1 422 Unprocessable Entity');
            echo json_encode(['message' => 'csvFile required']);
            die();
        }
        if($_FILES['csvFile']['type'] != 'text/csv') {
            header('HTTP/1.1 422 Unprocessable Entity');
            echo json_encode(['message' => 'csvFile should be the type of text/csv']);
            die();
        }

        $targetDir = config('storage');
        $targetFile = $targetDir . '/' . basename($_FILES['csvFile']['name']);
        move_uploaded_file($_FILES['csvFile']['tmp_name'], $targetFile);

        if(config('database')['driver'] == 'mysql') {
            $credentials = config('database');
        
            $connection = (
                new DBConnection(new MySQLDriver(
                    $credentials['host'],
                    $credentials['port'],
                    $credentials['database'],
                    $credentials['username'],
                    $credentials['password']
                ))
            )->getConnection();
        }
        
        $message = 'Table promotions already exists';

        $tableExists = $connection->query('SHOW TABLES LIKE "promotions"')->fetch(PDO::FETCH_ASSOC);
        if($tableExists === false) {
            (new CSVUploaded($targetFile, $connection))->handle();
            header('HTTP/1.1 201 Created');
            $message = 'Data processed successfuly';
        }

        echo json_encode(['message' => $message]);
    }
}