<?php

namespace App\Controllers;

use PDO;
use Countable;
use App\Models\Post;
use Core\Http\Response;
use App\Events\CSVUploaded;
use Core\Support\Collection;
use Core\Database\MySQLDriver;
use Core\Database\DBConnection;

class ProcessController
{
    public function store()
    {
        $response = new Response();

        if(count($_FILES) == false || !isset($_FILES['csvFile'])) {
            $response->setStatusCode(422);
            $response->setContent(json_encode(['message' => 'csvFile required']));

            return $response->send();
        }
        if($_FILES['csvFile']['type'] != 'text/csv') {
            $response->setStatusCode(422);
            $response->setContent(json_encode(['message' => 'csvFile should be the type of text/csv']));

            return $response->send();
        }

        $targetDir = config('storage');
        $targetFile = $targetDir . '/' . basename($_FILES['csvFile']['name']);
        move_uploaded_file($_FILES['csvFile']['tmp_name'], $targetFile);

        $connection = (
            new DBConnection(config('database'))
        )->getConnection();

        $message = 'Table promotions already exists';

        $tableExists = $connection->query('SHOW TABLES LIKE "promotions"')->fetch(PDO::FETCH_ASSOC);
        if($tableExists === false) {
            (new CSVUploaded($targetFile, $connection))->handle();
            $response->setStatusCode(201);
            $message = 'Data processed successfuly';
        }

        $response->setContent(json_encode(['message' => $message]));
        return $response->send();
    }
}