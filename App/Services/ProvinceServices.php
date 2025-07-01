<?php

namespace App\Services;

if (!defined('Auth_Access')) {
    die("Access Denied");
}

use PDOException;
use App\Libs\FileHandling;
use App\Utilities\Response;

class ProvinceServices extends BaseServices
{
    protected static $table = 'province';

    public  function getAll()
    {
        $pdo = self::db();
        $sql = "SELECT * FROM  ". self::$table ." ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll($pdo::FETCH_OBJ);
    }

    public  function create($data)
    {
        try {
            $pdo = self::db();
            $sql = "INSERT INTO  ". self::$table ."  (name) VALUES(?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$data['name']]);
            return $stmt->rowCount();
        } catch (PDOException $e) {
            FileHandling::WriteErrorLog($e->getMessage(), __FILE__, __LINE__);
            Response::RespondeAndDie("Plaese Contact Administrator", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update($id, $name)
    {
        try {
            $pdo = self::db();
            $sql = "UPDATE  ". self::$table ."  SET name = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$name, $id]);
            return $stmt->rowCount();
        } catch (PDOException $e) {
            FileHandling::WriteErrorLog($e->getMessage(), __FILE__, __LINE__);
            Response::RespondeAndDie("Plaese Contact Administrator", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
        public static function getProvince($id)
    {
        try {
            $pdo = self::db();
            $sql = "SELECT *  FROM  ". CityServices::$table ."  WHERE province_id = ? ORDER BY id ASC LIMIT 1";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch($pdo::FETCH_OBJ);
        } catch (\PDOException $e) {
            FileHandling::WriteErrorLog($e->getMessage(), __FILE__, __LINE__);
            Response::RespondeAndDie("Plaese Contact Administrator", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
}
