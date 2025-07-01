<?php

namespace App\Services;

use PDOException;
use App\Utilities\Response;
use App\Libs\FileHandling;

if (!defined('Auth_Access')) {
    die("Access Denied");
}




class CityServices extends BaseServices
{
    protected static $table = 'city';

    public  function getAll($data = null)
    {
        $where = '';
        $province_id = $data['province_id'] ?? null;
        if (!is_null($province_id) && is_numeric($province_id)) {
            $where = "WHERE province_id = {$province_id}";
        }
        $pdo = self::db();
        $sql = "SELECT * FROM city $where";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll($pdo::FETCH_OBJ);
    }
    public function create($data)
    {
        try {
            $pdo = self::db();
            $sql = "INSERT INTO city (province_id,name) VALUES(?,?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$data['province_id'], $data['name']]);
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
            $sql = "UPDATE city SET name = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$name, $id]);
            return $stmt->rowCount();
        } catch (\PDOException $e) {
            FileHandling::WriteErrorLog($e->getMessage(), __FILE__, __LINE__);
            Response::RespondeAndDie("Plaese Contact Administrator", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public static function isCityExistByNameAndProvince($name, $province_id)
    {
        try {
            $pdo = self::db();
            $sql = "SELECT COUNT(*) FROM city WHERE name = ? AND province_id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$name, $province_id]);
            return $stmt->fetchColumn() > 0;
        } catch (\PDOException $e) {
            FileHandling::WriteErrorLog($e->getMessage(), __FILE__, __LINE__);
            Response::RespondeAndDie("Please Contact Administrator", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
