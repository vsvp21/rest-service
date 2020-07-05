<?php

namespace App\Models;

use Core\Database\AbstractModel;

class Promotion extends AbstractModel
{
    protected $tableName = 'promotions';
    protected $fillable = [
        'id',
        'name',
        'starts_at',
        'ends_at',
        'status'
    ];

    public $id;
    public $name;
    public $starts_at;
    public $ends_at;
    public $status;
    public $created_at;
    public $updated_at;

    public function buildUrl() {
        return $this->id . '-' . slug($this->name);
    }
}