<?php
namespace App\Services;
if (!defined('Auth_Access')) {
    die("Access Denied");
}

use App\Utilities\Response;
use App\Libs\FileHandling;

abstract class BaseServices{
    protected static $table;
    
    protected static function db()
    {
        return (new \App\Config\dbConnection())->getconnection();
    }
    abstract function getAll();
    abstract function create($data);
    abstract function update($id,$name);
     public static function isExist($value, $column = 'id')
    {
        try {
            if (!in_array($column, ['id', 'name','province_id'])) {
                throw new \InvalidArgumentException("Invalid column name");
            }

            $sql = "SELECT COUNT(*) FROM " . static::$table . " WHERE {$column} = ?";
            $stmt = self::db()->prepare($sql);
            $stmt->execute([$value]);
            return $stmt->fetchColumn() > 0;
        } catch (\PDOException $e) {
            FileHandling::WriteErrorLog($e->getMessage(), __FILE__, __LINE__);
            Response::RespondeAndDie("Please Contact Administrator", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public static function getRow($value, $column = 'id')
    {
        try {
            if (!in_array($column, ['id', 'name'])) {
                throw new \InvalidArgumentException("Invalid column name");
            }

            $sql = "SELECT * FROM " . static::$table . " WHERE {$column} = ?";
            $stmt = self::db()->prepare($sql);
            $stmt->execute([$value]);

            return $stmt->fetch(\PDO::FETCH_OBJ);
        } catch (\PDOException $e) {
            FileHandling::WriteErrorLog($e->getMessage(), __FILE__, __LINE__);
            Response::RespondeAndDie("Please Contact Administrator", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public static function deleteRow($value, $column = 'id')
    {
        try {
            if (!in_array($column, ['id', 'name','province_id'])) {
                throw new \InvalidArgumentException("Invalid column name");
            }

            $sql = "DELETE FROM " . static::$table . " WHERE {$column} = ?";
            $stmt = self::db()->prepare($sql);
            $stmt->execute([$value]);

            return $stmt->rowCount();
        } catch (\PDOException $e) {
            FileHandling::WriteErrorLog($e->getMessage(), __FILE__, __LINE__);
            Response::RespondeAndDie("Please Contact Administrator", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}