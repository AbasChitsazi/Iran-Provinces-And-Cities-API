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

    public  function getAll()
    {
        $pdo = self::db();
        $sql = "SELECT * FROM province";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll($pdo::FETCH_OBJ);
    }

    public  function create($data)
    {
        try {
            $pdo = self::db();
            $sql = "INSERT INTO province (name) VALUES(?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$data['name']]);
            return $stmt->rowCount();
        } catch (PDOException $e) {
            FileHandling::WriteErrorLog($e->getMessage(), __FILE__, __LINE__);
            Response::RespondeAndDie("Plaese Contact Administrator", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function delete($id)
    {
        try {
            $pdo = self::db();
            $sql = "DELETE  FROM province WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            FileHandling::WriteErrorLog($e->getMessage(), __FILE__, __LINE__);
            Response::RespondeAndDie("Plaese Contact Administrator", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function update($id, $name)
    {
        try {
            $pdo = self::db();
            $sql = "UPDATE province SET name = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$name, $id]);
            return $stmt->rowCount();
        } catch (PDOException $e) {
            FileHandling::WriteErrorLog($e->getMessage(), __FILE__, __LINE__);
            Response::RespondeAndDie("Plaese Contact Administrator", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public static function isProvinceExistbyId($id)
    {
        try {
            $pdo = self::db();
            $sql = "SELECT COUNT(*) FROM province WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            FileHandling::WriteErrorLog($e->getMessage(), __FILE__, __LINE__);
            Response::RespondeAndDie("Plaese Contact Administrator", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public static function isProvinceExistbyName($name)
    {
        try {
            $pdo = self::db();
            $sql = "SELECT COUNT(*) FROM province WHERE name = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$name]);
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            FileHandling::WriteErrorLog($e->getMessage(), __FILE__, __LINE__);
            Response::RespondeAndDie("Plaese Contact Administrator", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public static function getrowbyName($data)
    {
        try {
            $pdo = self::db();
            $sql = "SELECT * FROM province WHERE name = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$data]);
            return $stmt->fetch($pdo::FETCH_OBJ);
        } catch (PDOException $e) {
            FileHandling::WriteErrorLog($e->getMessage(), __FILE__, __LINE__);
            Response::RespondeAndDie("Plaese Contact Administrator", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public static function getrowbyId($id)
    {
        try {
            $pdo = self::db();
            $sql = "SELECT * FROM province WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch($pdo::FETCH_OBJ);
        } catch (PDOException $e) {
            FileHandling::WriteErrorLog($e->getMessage(), __FILE__, __LINE__);
            Response::RespondeAndDie("Plaese Contact Administrator", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
