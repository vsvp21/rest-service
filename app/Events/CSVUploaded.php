<?php

namespace App\Events;

use DateTime;
use App\Models\Promotion;
use Core\Contracts\Event;

class CSVUploaded implements Event
{
    public $resource;
    public $connection;

    public function __construct($resource, $connection)
    {
        $this->resource = $resource;
        $this->connection = $connection;
    }

    public function handle()
    {
        $this->connection->query('
            CREATE TABLE promotions (
                id INT(10) UNSIGNED NOT NULL,
                name VARCHAR(191) NOT NULL,
                starts_at INT(11) NOT NULL,
                ends_at INT(11) NOT NULL,
                status TINYINT NOT NULL,
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL,
                PRIMARY KEY (id)
            ) CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB
        ');

        if (($handle = fopen($this->resource, "r")) !== false) {
            fgetcsv($handle, 8000, ';');
            while (($data = fgetcsv($handle, 8000, ';')) !== false) {
                $promotion = new Promotion();
                $promotion->id = $data[0];
                $promotion->name = $data[1];
                $promotion->starts_at = DateTime::createFromFormat('d-m-Y', $data[2])->getTimestamp();
                $promotion->ends_at = DateTime::createFromFormat('d-m-Y', $data[3])->getTimestamp();
                $promotion->status = $data[4] == 'On' ? 1 : 0;

                $promotion->save();
            }
            fclose($handle);
        }
    }
}