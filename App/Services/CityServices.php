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

    public function getAll($data = [])
{
    $params = [];
    $where = '';
    $limit = '';
    
    $province_id = $data['province_id'] ?? null;
    $page = $data['page'] ?? null;
    $pagesize = $data['pagesize'] ?? null;
    $fields = $data['fields'];
    $order_by = $data['order_by'] ?? null;
    
    if (!is_null($province_id) && is_numeric($province_id)) {
        $where = "WHERE province_id = :province_id";
        $params[':province_id'] = $province_id;
    }

    if (!is_null($page) && is_numeric($page) && !is_null($pagesize) && is_numeric($pagesize)) {
        $offset = $pagesize * ((int)$page - 1);
        $limit = "LIMIT :offset, :pagesize";
        $params[':offset'] = (int)$offset;
        $params[':pagesize'] = (int)$pagesize;
    }
    try{
    $pdo = self::db();
    $sql = "SELECT $fields FROM ". self::$table ." $where $order_by $limit";
    $stmt = $pdo->prepare($sql);

    foreach ($params as $key => $value) {
        $param_type = is_int($value) ? \PDO::PARAM_INT : \PDO::PARAM_STR;
        $stmt->bindValue($key, $value, $param_type);
    }

    $stmt->execute();
    return $stmt->fetchAll($pdo::FETCH_OBJ);
    }
    catch(PDOException $e){
        FileHandling::WriteErrorLog($e->getMessage(), __FILE__, __LINE__);
        Response::RespondeAndDie("Plaese Contact Administrator", Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
    public function create($data)
    {
        try {
            $pdo = self::db();
            $sql = "INSERT INTO  ". self::$table ."  (province_id,name) VALUES(?,?)";
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
            $sql = "UPDATE  ". self::$table ."  SET name = ? WHERE id = ?";
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
            $sql = "SELECT COUNT(*) FROM  ". self::$table ."  WHERE name = ? AND province_id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$name, $province_id]);
            return $stmt->fetchColumn() > 0;
        } catch (\PDOException $e) {
            FileHandling::WriteErrorLog($e->getMessage(), __FILE__, __LINE__);
            Response::RespondeAndDie("Please Contact Administrator", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public static function getColumns()
    {
        $pdo = self::db();
        $sql = "SELECT COLUMN_NAME FROM information_schema.columns where table_name = '" . self::$table . "'" ;
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
    }
}
