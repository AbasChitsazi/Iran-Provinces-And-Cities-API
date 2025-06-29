<?php
namespace App\Services;

use App\Config\dbConnection;

abstract class BaseServices{
    protected $Primery_Key = "id";
    
    public static function db()
    {
        return dbConnection::getConnection();
    }
    abstract function getAll();
    abstract function create($data);
    // abstract function findById($id);
    // abstract function delete($id);
    // abstract function update($id);
}