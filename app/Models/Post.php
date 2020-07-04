<?php

namespace App\Models;

use Core\Database\AbstractModel;

class Post extends AbstractModel
{
    protected $tableName = 'posts';
    protected $fillable = [
        'title',
        'body'
    ];
    
    public $id;
    public $title;
    public $body;
    public $created_at;
    public $updated_at;
}