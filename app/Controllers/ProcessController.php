<?php

namespace App\Controllers;

use App\Models\Post;
use Core\Support\Collection;

class UploadController
{
    public function getCsv()
    {
        dump($_FILES);
    }

    public function postCsv()
    {
        dump(1);
    }

    public function putCsv() {
        dump(2);
    }
}